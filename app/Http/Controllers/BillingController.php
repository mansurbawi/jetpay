<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing;
use App\Detailbilling;
use App\Transaction;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $post = Billing::select('billings.id as idBill', 'billings.nomorpembayaran', 'billings.created_at', 'billings.nama', 'billings.id_type', 'billings.status', 'billings.prioritas', 'billings.totalnominal', DB::Raw('SUM(transactions.totalnominal) as totalbayar') )->where('status', '=', 'BELUM')
                                ->leftJoin('transactions', 'transactions.billing_id', 'billings.id')
                                ->whereNull('billings.deleted_at')
                                ->groupBy('billings.id')
                                ->groupBy('transactions.billing_id')
                                ->groupBy('billings.nomorpembayaran')
                                ->groupBy('billings.created_at')
                                ->groupBy('billings.nama')
                                ->get();
        var_dump ($post);
                                //  return view ('billing.billings');
    }


    public function lunas()
    {
        return view ('billing.billpaidof');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function noactive(Request $request)
    {
        return view ('billing.billnoactive');
    }

    public function restore($id)
    {
        Billing::withTrashed()->find($id)->restore();
        Detailbilling::withTrashed()->firstWhere('billings_id', '=', $id)->restore();
        return redirect()->route('billnoactive');
    }    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        return view ('billing.import');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('billing.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $cek = self::cekBilling($request->va_number, $request->jenis_tagihan, $request->prioritas);
        if ($cek == "True") {
          return redirect()->back()->with('alert', 'Mohon Maaf Data Sudah ada dengan prioritas yang sama!');  
        } 
        $this->validate($request, [
            'va_number'	=> 'required',
            'jenis_tagihan' => 'required',
            'prioritas' => 'required',
            'no_induk' => 'required',
            'nama' => 'required',
            'fakultas' => 'required',
            'jurusan' => 'required',
            'strata' => 'required',
            'periode' => 'required',
            'angkatan' => 'required',
            'nominal' => 'required',
            'kodetagihan' => 'required',
            'desk_pendek' => 'required',
        ]);
        $id = Uuid::uuid4()->getHex();
        $aksi = $request->jenis_tagihan;
        $satuan = self::$aksi($request->nominal);
        Billing::create([
            'id' => $id,
            'nomorpembayaran' => $request->va_number,
            'id_type' => $request->jenis_tagihan,
            'prioritas' => $request->prioritas,
            'nomorinduk'=> $request->no_induk,
            'nama' => $request->nama,
            'fakultas' => $request->fakultas,
            'jurusan' => $request->jurusan,
            'strata' => $request->strata,
            'periode' => $request->periode,
            'angkatan' => $request->angkatan,
            'totalnominal' => $request->nominal,
            'user_id' => Auth::user()->id,
            'satuan' => $satuan,
            'status' => "BELUM"
                ]);
        Detailbilling::create([
            'billings_id' => $id,
            'kodedetailtagihan' => $request->kodetagihan,
            'deskripsipendek' => $request->desk_pendek,
            'deskripsipanjang' => $request->desk_panjang,
            'nominal' => $request->nominal
        ]);
        return redirect()->route('billings');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $post = Billing::find($id);
        $post->delete();
        $post2 = Detailbilling::firstWhere('billings_id', '=', $id);
        $post2->delete();
        return redirect()->route('billings');       
    }

    public function edit($id)
    {
        $post = DB::table('billings')->join('detailbillings', 'detailbillings.billings_id', 'billings.id')->where('billings.id', '=', $id)->get();
        return view('billing.edit')->with('post', $post);
    }

    public function update(Request $request)
    {
        Billing::where('id', '=', $request->id )->update([
            'nomorpembayaran' => $request->va_number,
            'id_type' => $request->jenis_tagihan,
            'prioritas' => $request->prioritas,
            'nomorinduk'=> $request->no_induk,
            'nama' => $request->nama,
            'fakultas' => $request->fakultas,
            'jurusan' => $request->jurusan,
            'strata' => $request->strata,
            'periode' => $request->periode,
            'angkatan' => $request->angkatan,
            'totalnominal' => $request->nominal,
                ]);
        Detailbilling::where('billings_id', '=', $request->id )->update([
            'kodedetailtagihan' => $request->kodetagihan,
            'deskripsipendek' => $request->desk_pendek,
            'deskripsipanjang' => $request->desk_panjang,
            'nominal' => $request->nominal
        ]);
        return redirect()->route('billings');           
    }

    public function lunasall(Request $request)
    {
     $columns = array( 
                0 =>'nomorpembayaran', 
                1 =>'created_at',
                2=> 'nama',
                3=> 'tipe',
                4=> 'status',
                5=> 'prioritas',
                6=> 'totalnominal',
                7=> 'action',
            );
    $totalData = DB::table('billings')->where('status', '=', 'LUNAS')->count();
    $totalFiltered = $totalData;
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $order = $columns[6];
    $dir = $request->input('order.0.dir');
    if(empty($request->input('search.value')))
    {
        $posts = DB::table('billings')->where('status', '=', 'LUNAS')
                                         ->offset($start)
                                         ->limit($limit)
                                         ->orderBy($order,$dir)
                                         ->get();
    } else {
    $search = $request->input('search.value'); 
    $posts = DB::table('billings')->where('status', '=', 'LUNAS')
                                    ->where('nama','LIKE',"%{$search}%")
                                     ->orWhere('nomorpembayaran', 'LIKE',"%{$search}%")
                                     ->offset($start)
                                     ->limit($limit)
                                     ->orderBy($order,$dir)
                                     ->get();
    $posts = DB::table('billings')->where('status', '=', 'LUNAS')
                                    ->where('nama','LIKE',"%{$search}%")
                                     ->orWhere('nomorpembayaran', 'LIKE',"%{$search}%")
                                     ->offset($start)
                                     ->limit($limit)
                                     ->orderBy($order,$dir)
                                     ->count();   
    }
        $data = array();
        if(!empty($posts))
        {
            $no =1;
            foreach ($posts as $post)
            {
                $show =  "<a href='detailPembayaran/$post->id' class='btn btn-primary'>Detail Pembayaran</a>";


                $nestedData['nomorpembayaran'] =$post->nomorpembayaran; 
                $nestedData['created_at'] = $post->created_at;
                $nestedData['nama'] = $post->nama;
                $nestedData['tipe'] = $post->id_type;
                $nestedData['status'] = $post->status;
                $nestedData['prioritas'] = $post->prioritas;
                $nestedData['totalnominal'] = number_format($post->totalnominal);
                $nestedData['action'] = $show;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
    }

    public function notactiveall(Request $request)
    {
     $columns = array( 
                0 =>'nomorpembayaran', 
                1 =>'created_at',
                2=> 'nama',
                3=> 'tipe',
                4=> 'status',
                5=> 'prioritas',
                6=> 'totalnominal',
                7=> 'action',
            );
    $totalData = DB::table('billings')->whereNotNull('deleted_at')->count();
    $totalFiltered = $totalData;
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $order = $columns[6];
    $dir = $request->input('order.0.dir');
    if(empty($request->input('search.value')))
    {
        $posts = DB::table('billings')->whereNotNull('deleted_at')
                                         ->offset($start)
                                         ->limit($limit)
                                         ->orderBy($order,$dir)
                                         ->get();
    } else {
    $search = $request->input('search.value'); 
    $posts = DB::table('billings')->whereNotNull('deleted_at')
                                    ->where('nama','LIKE',"%{$search}%")
                                     ->orWhere('nomorpembayaran', 'LIKE',"%{$search}%")
                                     ->offset($start)
                                     ->limit($limit)
                                     ->orderBy($order,$dir)
                                     ->get();
    $posts = DB::table('billings')->whereNotNull('deleted_at')
                                    ->where('nama','LIKE',"%{$search}%")
                                     ->orWhere('nomorpembayaran', 'LIKE',"%{$search}%")
                                     ->offset($start)
                                     ->limit($limit)
                                     ->orderBy($order,$dir)
                                     ->count();   
    }
        $data = array();
        if(!empty($posts))
        {
            $no =1;
            foreach ($posts as $post)
            {
                $show =  "<a href='billings/restore/$post->id' class='btn btn-danger'>Restore</a>";


                $nestedData['nomorpembayaran'] =$post->nomorpembayaran; 
                $nestedData['created_at'] = $post->created_at;
                $nestedData['nama'] = $post->nama;
                $nestedData['tipe'] = $post->id_type;
                $nestedData['status'] = $post->status;
                $nestedData['prioritas'] = $post->prioritas;
                $nestedData['totalnominal'] = number_format($post->totalnominal);
                $nestedData['action'] = $show;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
    }

    public function billAll(Request $request)
    {
     $columns = array( 
                0 =>'nomorpembayaran', 
                1 =>'updated_at',
                2=> 'nama',
                3=> 'tipe',
                4=> 'status',
                5=> 'prioritas',
                6=> 'totalnominal',
                7=> 'sisa',
                9=> 'created_at',
                9=> 'action',
            );
    $totalData = Billing::select('id as idBill', 'nomorpembayaran', 'created_at', 'updated_at', 'nama', 'id_type', 'status', 'prioritas', 'totalnominal')->where('status', '=', 'BELUM')->whereNull('billings.deleted_at')->count();
    $totalFiltered = $totalData;
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $order = $columns[1];
    $dir = $request->input('order.0.dir');
    if(empty($request->input('search.value')))
    {
        $posts = Billing::select('id as idBill', 'nomorpembayaran', 'created_at', 'updated_at', 'nama', 'id_type', 'status', 'prioritas', 'totalnominal')
                                ->where('status', '=', 'BELUM')
                                 ->whereNull('deleted_at')
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order, 'DESC')
                                ->get();
    } else {
    $search = $request->input('search.value'); 
    $posts = Billing::select('id as idBill', 'nomorpembayaran', 'created_at', 'updated_at', 'nama', 'id_type', 'status', 'prioritas', 'totalnominal')
                                ->where('status', '=', 'BELUM')
                                ->whereNull('deleted_at')
                                ->where('nama','LIKE',"%{$search}%")
                                 ->orWhere('nomorpembayaran', 'LIKE',"%{$search}%")
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy($order,$dir)
                                 ->get();
    $posts = Billing::select('id as idBill', 'nomorpembayaran', 'created_at', 'updated_at', 'nama', 'id_type', 'status', 'prioritas', 'totalnominal')
                                ->where('status', '=', 'BELUM')
                                ->whereNull('deleted_at')
                                ->where('nama','LIKE',"%{$search}%")
                                 ->orWhere('nomorpembayaran', 'LIKE',"%{$search}%")
                                 ->offset($start)
                                 ->limit($limit)
                                 ->orderBy($order,$dir)
                                 ->count();   
    }
        $data = array();
        if(!empty($posts))
        {
            $no =1;
            foreach ($posts as $post)
            {
                $show =  "<div class='btn-group'><a href='billings/delete/$post->idBill' class='btn btn-danger'>Hapus</a>
                          <a href='billings/edit/$post->idBill' class='btn btn-warning'>Edit</a></div>";

                $totalBayar = Transaction::where('billing_id', '=', $post->idBill)->sum('totalnominal');
                $nestedData['nomorpembayaran'] =$post->nomorpembayaran; 
                $nestedData['updated_at'] = $post->created_at;
                $nestedData['nama'] = $post->nama;
                $nestedData['tipe'] = $post->id_type;
                $nestedData['status'] = $post->status;
                $nestedData['prioritas'] = $post->prioritas;
                $nestedData['totalnominal'] = $post->totalnominal;
                $nestedData['sisa'] = $post->totalnominal-$totalBayar;
                $nestedData['created_at'] = $post->created_at;
                $nestedData['action'] = $show;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
    }

    public function full($id)
    {
        return $id;
    }

    public function open($id)
    {
        return $id;
    }

    public function partial($id)
    {
        return $id;
    }

    public function partial2($id)
    {
        $ids = $id/2;
        return $ids;
    }

    public function partial3($id)
    {
        $ids = $id/3;
        return $ids;
    }

    public function partial4($id)
    {
        $ids = $id/4;
        return $ids;
    }        

    public function partial5($id)
    {
        $ids = $id/5;
        return $ids;
    }        

    public function partial6($id)
    {
        $ids = $id/6;
        return $ids;
    }        

    public function partial7($id)
    {
        $ids = $id/7;
        return $ids;
    }        

    public function partial8($id)
    {
        $ids = $id/8;
        return $ids;
    }        

    public function partial9($id)
    {
        $ids = $id/9;
        return $ids;
    }        

    public function partial10($id)
    {
        $ids = $id/10;
        return $ids;
    }        

    public function partial11($id)
    {
        $ids = $id/11;
        return $ids;
    }        

    public function partial12($id)
    {
        $ids = $id/12;
        return $ids;
    }

    public function cekBilling($nomorpembayaran, $type, $prioritas)
      {
          $post = Billing::where('nomorpembayaran', '=', $nomorpembayaran)->where('prioritas', '=', $prioritas)->where('id_type', '=', $type)->where('status', '=', 'BELUM')->count();
          if ($post > 0){
            return "True";
          } else { return "False"; }
      }  
}
