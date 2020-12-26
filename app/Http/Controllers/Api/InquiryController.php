<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\PartialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Billing;
use App\Transaction;
use Ramsey\Uuid\Uuid;


class InquiryController extends Controller
{
    protected $partialController;

    public function _contruct(PartialController $partialController)
    {
        $this->PartialController = $partialController;
    }

    public function service(Request $request)
    {

	$log_directory = '/var/log/h2h/'; // 
	
	$data = $request->input('request');
	$data2 = json_decode($data);
	self::debugLog('REQUEST: '.$data);
	
    $aksi = $request->input('action');
    if ($aksi == 'inquiry'){
	$arey = self::inquiry($data2->kodeBank, $data2->kodeChannel, $data2->kodeTerminal, $data2->nomorPembayaran, $data2->tanggalTransaksi, $data2->idTransaksi, $data);
    } else if($aksi == 'payment'){
    $arey = self::payment($data2->kodeBank, $data2->kodeChannel, $data2->kodeTerminal, $data2->nomorPembayaran, $data2->idTagihan, $data2->tanggalTransaksi, $data2->idTransaksi, $data2->totalNominal, $data2->nomorJurnalPembukuan, $data2->rincianTagihan, $data2->checksum);
    } else if($aksi == 'reversal'){
    $arey = self::reversal($data2->kodeBank, $data2->kodeChannel, $data2->kodeTerminal, $data2->nomorPembayaran, $data2->idTagihan, $data2->tanggalTransaksi, $data2->tanggalTransaksiAsal, $data2->nomorJurnalPembukuan, $data2->idTransaksi, $data2->totalNominal);
    }
    
	 return $arey;
    }

	public function debugLog($o) {

	 	activity()->log($o);

	} 

    public function inquiry($kodeBank, $kodeChannel, $kodeTerminal, $nomorPembayaran, $tanggalTransaksi, $idTransaksi, $data)
       {

        $kampus     = 'Universitas Test';
        $secret_key = 'ahjsg@6567JHJ47KJHksa;pd'; // contoh
         
        //$allowed_ips = array('119.2.80.1', '119.2.80.2', '119.2.80.3'); // ini adalah IP Switching Makara. Silahkan ditambahkan IP mana saja yang diperbolehkan akses.
        $allowed_ips               = array('127.0.0.1','::1'); // Ini IP localhost untuk testing
        $allowed_collecting_agents = array('BSM'); // Bank mana aja yg bekerja sama.
        $allowed_channels          = array('TELLER', 'IBANK', 'ATM', 'SMS','MBANK');     

        if (empty($kodeBank) || empty($kodeChannel) || empty($kodeTerminal) || empty($nomorPembayaran) || empty($tanggalTransaksi) || empty($idTransaksi)) {
            response(array(
                'code'    => '30',
                'message' => 'Salah format message dari bank'
            ));
        }
        // cek apakah bank terdaftar?
        if (!in_array($kodeBank, $allowed_collecting_agents)) {
        return array(
                'code'    => '31',
                'message' => 'Collecting agent tidak terdaftar di ' . $kampus
            ); exit();
        }
        // cek apakah channel disupport?
        if (!in_array($kodeChannel, $allowed_channels)) {
            return array(
                'code'    => '58',
                'message' => 'Channel tidak diperbolehkan di ' . $kampus
            ); exit();
        }
        // cek apakah ada data tagihan?
        $isAdaTagihan = true; // silahkan cek di database apakah ada tagihan dengan nomor pembayaran tersebut?
        if ($isAdaTagihan == false) {
            response(array(
                'code'    => '14',
                'message' => 'Tagihan tidak ditemukan di ' . $kampus
            ));
        }
        // cek apakah masih dalam periode pembayaran yang diperbolehkan?
        $isDalamPeriodepembayaran = true; // silahkan cek di database apakah tagihan masih dalam periode pembayaran?
        if ($isDalamPeriodepembayaran == false) {
            response(array(
                'code'    => '14',
                'message' => 'Tidak berlaku periode bayar di ' . $kampus
            ));
        }
        // cek apakah sudah lunas apa belum?
        $billcount = DB::table('billings')->leftjoin('detailbillings', 'detailbillings.billings_id', 'billings.id')->where('nomorpembayaran', '=', $nomorPembayaran)->where('status', '=', 'BELUM')->count();
        $bill = DB::table('billings')->leftjoin('detailbillings', 'detailbillings.billings_id', 'billings.id')->where('nomorpembayaran', '=', $nomorPembayaran)->where('status', '=', 'BELUM')->first();
        if ($billcount == 0){
            return array(
                'code'    => '14',
                'message' => 'Tagihan yang bisa dibayar tidak ditemukan di ' . $kampus); 
            exit();
        } else {
        // foreach ($bill as $bills) {
            if ($bill->status == "LUNAS"){
                return array(
                    'code'    => '88',
                    'message' => 'Tagihan sudah terbayar di ' . $kampus
                ); exit();                
            }
            $dataTagihan = array( // silahkan diambil dari database untuk datagihannya
            'nomorPembayaran' => $bill->nomorpembayaran,
            'idTagihan'       => $bill->idtagihan,
            'nomorInduk'      => $bill->nomorinduk,
            'nama'            => $bill->nama,
            'fakultas'        => $bill->fakultas,
            'jurusan'         => $bill->jurusan,
            'strata'          => $bill->strata,
            'periode'         => $bill->periode,
            'angkatan'        => $bill->angkatan,
            'totalNominal'    => $bill->totalnominal,
            'rincianTagihan'  => array(
                array(
                    'kodeDetailTagihan' => $bill->kodedetailtagihan,
                    'deskripsiPendek'   => $bill->deskripsipendek,
                    'deskripsiPanjang'  => $bill->deskripsipanjang,
                    'nominal'           => $bill->nominal
                )
                )
            ); 
        // }
        }
        $ipClient = self::getIp();        
        self::debugLog('RESPONSE : '.$data.' - '.$ipClient);
        $arr = array('code'    => '00','message' => 'Pembayaran sukses dicatat di '.$kampus,'data'    => $dataTagihan);
        return $arr;
       } 
       
//PAYMENT --------------------------------------------------------------------------
       public function payment($kodeBank, $kodeChannel, $kodeTerminal, $nomorPembayaran, $idTagihan, $tanggalTransaksi, $idTransaksi, $totalNominal, $nomorJurnalPembukuan, $rincianTagihan, $checksum)
         {
        $kampus     = 'Universitas Test';
        $secret_key = 'ahjsg@6567JHJ47KJHksa;pd'; // contoh
         
        //$allowed_ips = array('119.2.80.1', '119.2.80.2', '119.2.80.3'); // ini adalah IP Switching Makara. Silahkan ditambahkan IP mana saja yang diperbolehkan akses.
        $allowed_ips               = array('127.0.0.1','::1'); // Ini IP localhost untuk testing
        $allowed_collecting_agents = array('BSM'); // Bank mana aja yg bekerja sama.
        $allowed_channels          = array('TELLER', 'IBANK', 'ATM', 'SMS','MBANK');                 
        if (empty($kodeBank) || empty($kodeChannel) || empty($kodeTerminal) || empty($nomorPembayaran) || empty($tanggalTransaksi) || empty($idTransaksi) || empty($totalNominal) || empty($nomorJurnalPembukuan) || empty($rincianTagihan)) {
            response(array(
                'code'    => '30',
                'message' => 'Salah format message dari bank'
            ));
        }
        // cek apakah bank terdaftar?
        if (!in_array($kodeBank, $allowed_collecting_agents)) {
            response(array(
                'code'    => '31',
                'message' => 'Collecting agent tidak terdaftar di ' . $kampus
            ));
        }
        // cek apakah channel disupport?
        if (!in_array($kodeChannel, $allowed_channels)) {
            return array(
                'code'    => '58',
                'message' => 'Channel tidak diperbolehkan di ' . $kampus
            ); exit();
        }
 
        $resp = DB::table('billings')->where('nomorpembayaran', '=', $nomorPembayaran)->where('status', '=', 'BELUM')->first();
        // foreach ($res as $resp) {
                $idbil = $resp->id;
                $tipeBayar =  $resp->id_type;
                $rec = DB::table('detailbillings')->where('billings_id','=', $idbil)->get();
                foreach ($rec as $recs) {
                    $kodeDetailTagihan = $recs->kodedetailtagihan;
                    $deskripsiPendek = $recs->deskripsipendek;
                    $deskripsiPanjang = $recs->deskripsipanjang;
                    $nominal = $recs->nominal;
                 }

            $tipePembayaran = substr($resp->id_type, 0, 7); 
            if ($tipePembayaran == "partial"){
                $total = Transaction::where('billing_id', '=', $idbil)->sum('totalnominal');
                $sisa = $resp->totalnominal - $total;
                $aksi2 = $resp->id_type;
                $part = self::$aksi2($totalNominal, $resp->satuan, $sisa);
                if ($part != "true"){
                return array(     
                    'code'    => '14',
                    'message' => 'Salah Jumlah Bayar di ' . $kampus
                ); exit();
                }
            } else if ($tipePembayaran == "full") {
                if ($totalNominal != $resp->totalnominal){
                    return array(     
                        'code'    => '14',
                        'message' => 'Salah Jumlah Bayar di ' . $kampus
                    ); exit();                    
                }
            }
            $dataTagihan = array( // silahkan diambil dari database untuk datagihannya
            'nomorPembayaran' => $resp->nomorpembayaran,
            'idTagihan'       => $resp->idtagihan,
            'nomorInduk'      => $resp->nomorinduk,
            'nama'            => $resp->nama,
            'fakultas'        => $resp->fakultas,
            'jurusan'         => $resp->jurusan,
            'strata'          => $resp->strata,
            'periode'         => $resp->periode,
            'angkatan'        => $resp->angkatan,
            'totalNominal'    => $resp->totalnominal,
            'rincianTagihan'  => array(
                array(
                    'kodeDetailTagihan' => $kodeDetailTagihan,
                    'deskripsiPendek'   => $deskripsiPendek,
                    'deskripsiPanjang'  => $deskripsiPanjang,
                    'nominal'           => $nominal
                )
                )
            );
            $_newDate = date("Y-m-d h:m:s",strtotime($tanggalTransaksi));    
            $date = date("Y-m-d h:m:s");         
            Transaction::create([
                                            'id' => Uuid::uuid4()->getHex(),
                                            'billing_id' => $resp->id,
                                            'nama' => $resp->nama,
                                            'kodebank' => $kodeBank,
                                            'kodechannel' => $kodeChannel,
                                            'kodeterminal' => $kodeTerminal,
                                            'nomorpembayaran' => $nomorPembayaran,
                                            'tanggaltransaksi' => $_newDate,
                                            'idtransaksi' => $idTransaksi,
                                            'totalnominal' => $totalNominal,
                                            'nomorjurnalpembukuan' => $nomorJurnalPembukuan,
                                            'checksum' => $checksum,
                                            'userid' => $resp->user_id,
                                            'choices' => 'payment'
                                            ]);
            if ($tipeBayar == "full" Or $sisa == $totalNominal){
            DB::table('billings')->where('id', '=', $resp->id)->update([ 'status' => 'LUNAS']);
            };
        // }
        // $jumlahRincian = count($rincianTagihan);
        // $total_nominal_rincian = 0;
        // for ($i = 0; $i < $jumlahRincian; $i++) {
        //     $total_nominal_rincian += $rincianTagihan[$i]['nominal'];
        // }
        // if ($total_nominal_rincian != $totalNominal) {
        //     response(array(
        //         'code'    => '30',
        //         'message' => 'Salah format nilai tagihan dari bank'
        //     ));
        // }
 
        // cek apakah ada data tagihan?
        // $isAdaTagihan = false; // silahkan cek di database apakah ada tagihan dengan nomor pembayaran tersebut?
        // if ($isAdaTagihan == false) {
        // return array(
        //         'code'    => '14',
        //         'message' => 'Tagihan tidak ditemukan di ' . $kampus
        //     ); exit();
        // }
        // // cek apakah masih dalam periode pembayaran yang diperbolehkan?
        // $isDalamPeriodepembayaran = true; // silahkan cek di database apakah tagihan masih dalam periode pembayaran?
        // if ($isDalamPeriodepembayaran == false) {
        //     response(array(
        //         'code'    => '14',
        //         'message' => 'Tidak berlaku periode bayar di ' . $kampus
        //     ));
        // }
        // // cek apakah sudah lunas apa belum?
        // $sudahLunas = false; // silahkan cek di database apakah tagihan tersebut sudah lunas apa belum.
        // if ($sudahLunas == true) {
        // $arr = array(
        //         'code'    => '88',
        //         'message' => 'Tagihan sudah terbayar di ' . $kampus
        //     );
        // return $arr; exit();
        // }
        // $dataTagihan = array( // silahkan diambil dari database untuk datagihannya
        //     'nomorPembayaran' => $nomorPembayaran,
        //     'idTagihan'       => 'abc123456',
        //     'nomorInduk'      => '123456',
        //     'nama'            => 'Abdulloh Umar',
        //     'fakultas'        => 'Ekonomi',
        //     'jurusan'         => 'Manajemen',
        //     'strata'          => 'S1',
        //     'periode'         => '2016/2017',
        //     'angkatan'        => '2015',
        //     'totalNominal'    => 1000000,
        //     'rincianTagihan'  => array(
        //         array(
        //             'kodeDetailTagihan' => '123',
        //             'deskripsiPendek'   => 'SPP',
        //             'deskripsiPanjang'  => 'Sumbangan Pembinaan Pendidikan',
        //             'nominal'           => 700000
        //         ),
        //         array(
        //             'kodeDetailTagihan' => '45678',
        //             'deskripsiPendek'   => 'GEDUNG',
        //             'deskripsiPanjang'  => 'Uang Gedung',
        //             'nominal'           => 300000
        //         )
        //     )
        // );
        // if (!is_array($dataTagihan)) {
        //     response(array(
        //         'code'    => '14',
        //         'message' => 'Tagihan yang bisa dibayar tidak ditemukan di ' . $kampus
        //     ));
        // }
        // $jumlahRincian = count($dataTagihan['rincianTagihan']);
        // $total_nominal_rincian = 0;
        // for ($i = 0; $i < $jumlahRincian; $i++) {
        //     $total_nominal_rincian += $dataTagihan['rincianTagihan'][$i]['nominal'];
        // }
        // if ($total_nominal_rincian != $dataTagihan['totalNominal']) {
        //     response(array(
        //         'code'    => '30',
        //         'message' => 'Salah format nilai tagihan dari ' . $kampus
        //     ));
        // }
 
        $prosesmasukkanDatabase = true; // Silahkan memasukkan data pembayaran ke database.
        if ($prosesmasukkanDatabase == false) {
            response(array(
                'code'    => '91',
                'message' => 'Database error saat proses FLAG Bayar di ' . $kampus
            ));
        }
        // unset($dataTagihan['rincianTagihan']); // rincianTagihan tidak diperlukan saat payment response
        self::debugLog('RESPONSE: PAYMENT');
        $arr = array(
            'code'    => '00',
            'message' => 'Pembayaran sukses dicatat di '.$kampus,
            'data'    => $dataTagihan
        );
        return $arr;
        }  

        //REVERSAL

        public function reversal($kodeBank, $kodeChannel, $kodeTerminal, $nomorPembayaran, $idTagihan, $tanggalTransaksi, $tanggalTransaksiAsal, $nomorJurnalPembukuan, $idTransaksi, $totalNominal)
        {

        $kampus     = 'Universitas Test';
        $secret_key = 'ahjsg@6567JHJ47KJHksa;pd'; // contoh
         
        //$allowed_ips = array('119.2.80.1', '119.2.80.2', '119.2.80.3'); // ini adalah IP Switching Makara. Silahkan ditambahkan IP mana saja yang diperbolehkan akses.
        $allowed_ips               = array('127.0.0.1','::1'); // Ini IP localhost untuk testing
        $allowed_collecting_agents = array('BSM'); // Bank mana aja yg bekerja sama.
        $allowed_channels          = array('TELLER', 'IBANK', 'ATM', 'SMS','MBANK');    

        if (empty($kodeBank) || empty($kodeChannel) || empty($kodeTerminal) || empty($nomorPembayaran) || empty($tanggalTransaksi) || empty($tanggalTransaksiAsal) || empty($nomorJurnalPembukuan) || empty($totalNominal)) {
            response(array(
                'code'    => '30',
                'message' => 'Salah format message dari bank'
            ));
        }
        // cek apakah bank terdaftar?
        if (!in_array($kodeBank, $allowed_collecting_agents)) {
            response(array(
                'code'    => '31',
                'message' => 'Collecting agent tidak terdaftar di '.$kampus
            ));
        }
        // cek apakah channel disupport?
        if (!in_array($kodeChannel, $allowed_channels)) {
            response(array(
                'code'    => '58',
                'message' => 'Collecting agent tidak terdaftar di '.$kampus
            ));
        }
        // cek apakah ada transaksi pembayaran tersebut sebelumnya?
        $isAdaDataPembayaranSebelumnya = true; // silahkan cek di database
        if ($isAdaDataPembayaranSebelumnya == false) {
            response(array(
                'code'    => '63',
                'message' => 'Reversal ditolak. Tagihan belum dibayar di '.$kampus
            ));
        }
 
        $isSudahDireversal = false; // cek di database apakah sudah dilakukan reversal sebelumnya
        if ($isSudahDireversal == true) {
            response(array(
                'code'    => '94',
                'message' => 'Reversal ditolak. Reversal sebelumnya sudah dilakukan di '.$kampus
            ));
        }
        $dataTagihan = array( // silahkan diambil dari database untuk datagihannya
            'nomorPembayaran' => $nomorPembayaran,
            'idTagihan'       => 'abc123456',
            'nomorInduk'      => '123456',
            'nama'            => 'Abdulloh Umar',
            'fakultas'        => 'Ekonomi',
            'jurusan'         => 'Manajemen',
            'strata'          => 'S1',
            'periode'         => '2016/2017',
            'angkatan'        => '2015',
            'totalNominal'    => 1000000
        );
        if (!is_array($dataTagihan)) {
            response(array(
                'code'    => '14',
                'message' => 'Tagihan tidak ditemukan di ' . $kampus
            ));
        }
        $prosesReversalDiDatabase = true; // Silahkan membatalkan data pembayaran ke database.
        if ($prosesReversalDiDatabase == false) {
            response(array(
                'code'    => '91',
                'message' => 'Database error saat proses FLAG Reversal di ' . $kampus
            ));
        }
        self::debugLog('RESPONSE: REVERSAL');
        $arr = array(
            'code'    => '00',
            'message' => 'Reversal sukses dilakukan di '.$kampus,
            'data'    => $dataTagihan
        );
        return $arr;
        }

public function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
    return request()->ip(); // it will return server ip when no client ip found
}

public function partial($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $satuan){
        return "true";
    } else { return "false"; }
}

public function partial2($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial3($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial4($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial5($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial6($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial7($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial8($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial9($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial10($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial11($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}

public function partial12($bayar, $satuan, $sisa)
{
    $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    if ($bayar <= $sisa){
        $hitung = $bayar/$satuan;
        if (in_array($hitung, $array)){
            return "true";
        }
    } else { return "false"; }
}    


}
