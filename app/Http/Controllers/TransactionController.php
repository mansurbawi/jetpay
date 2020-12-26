<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Billing;
use App\Detailbilling;
use App\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
       return view ('transaction.transaction');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        return view ('transaction.canceled');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $post = Transaction::find($id);
        $post->delete();
        return redirect()->route('transaction');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function alltrans(Request $request)
    {
     $columns = array( 
                0 =>'tanggaltransaksi', 
                1 =>'nomorpembayaran',
                2=> 'nomorjurnalpembukuan',
                3=> 'nama',
                4=> 'totalnominal',
                5=> 'choices',
                6=> 'created_at',
                7=> 'action',
            );
    $totalData = DB::table('transactions')->whereNull('deleted_at')->count();
    $totalFiltered = $totalData;
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $order = $columns[0];
    // $dir = $request->input('order.0.dir');
    $dir = "DESC";
    if(empty($request->input('search.value')))
    {
        $posts = DB::table('transactions')->whereNull('deleted_at')
                                         ->offset($start)
                                         ->limit($limit)
                                         ->orderBy($order,$dir)
                                         ->get();
    } else {
    $search = $request->input('search.value'); 
    $posts = DB::table('transactions')->whereNull('deleted_at')
                                     ->where('nomorpembayaran','LIKE',"%{$search}%")
                                     ->orWhere('nomorjurnalpembukuan', 'LIKE',"%{$search}%")
                                     ->offset($start)
                                     ->limit($limit)
                                     ->orderBy($order,$dir)
                                     ->get();
    $totalFiltered = DB::table('transactions')->whereNull('deleted_at')
                                     ->where('nomorpembayaran','LIKE',"%{$search}%")
                                     ->orWhere('nomorjurnalpembukuan', 'LIKE',"%{$search}%")
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
                $show =  "<a href='transactions/delete/$post->id' onclick='return  confirm(do you want to delete Y/N)' class='btn btn-danger'>Hapus</a>";


                $nestedData['tanggaltransaksi'] =$post->tanggaltransaksi; 
                $nestedData['nomorpembayaran'] = $post->nomorpembayaran;
                $nestedData['nomorjurnalpembukuan'] = $post->nomorjurnalpembukuan;
                $nestedData['nama'] = $post->nama;
                $nestedData['totalnominal'] = $post->totalnominal;
                $nestedData['choices'] = $post->choices;
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

    public function allCancel(Request $request)
    {
     $columns = array( 
                0 =>'tanggaltransaksi', 
                1 =>'nomorpembayaran',
                2=> 'nomorjurnalpembukuan',
                3=> 'nama',
                4=> 'totalnominal',
                5=> 'choices',
                6=> 'created_at',
                7=> 'action',
            );
    $totalData = DB::table('transactions')->whereNotNull('deleted_at')->count();
    $totalFiltered = $totalData;
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $order = $columns[0];
    $dir = $request->input('order.0.dir');
    if(empty($request->input('search.value')))
    {
        $posts = DB::table('transactions')->whereNotNull('deleted_at')
                                         ->offset($start)
                                         ->limit($limit)
                                         ->orderBy($order,$dir)
                                         ->get();
    } else {
    $search = $request->input('search.value'); 
    $posts = DB::table('transactions')->whereNotNull('deleted_at')
                                     ->where('nomorpembayaran','LIKE',"%{$search}%")
                                     ->orWhere('nomorjurnalpembukuan', 'LIKE',"%{$search}%")
                                     ->offset($start)
                                     ->limit($limit)
                                     ->orderBy($order,$dir)
                                     ->get();
    $totalFiltered = DB::table('transactions')->whereNotNull('deleted_at')
                                     ->where('nomorpembayaran','LIKE',"%{$search}%")
                                     ->orWhere('nomorjurnalpembukuan', 'LIKE',"%{$search}%")
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
                $show =  "<a href='transactions/restore/$post->id' onclick='return  confirm(do you want to delete Y/N)' class='btn btn-danger'>Restore</a>";


                $nestedData['tanggaltransaksi'] =$post->tanggaltransaksi; 
                $nestedData['nomorpembayaran'] = $post->nomorpembayaran;
                $nestedData['nomorjurnalpembukuan'] = $post->nomorjurnalpembukuan;
                $nestedData['nama'] = $post->nama;
                $nestedData['totalnominal'] = $post->totalnominal;
                $nestedData['choices'] = $post->choices;
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

}
