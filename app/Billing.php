<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
        'id', 'nomorpembayaran', 'id_type', 'prioritas', 'nomorinduk', 'nama', 'fakultas', 'jurusan', 'strata', 'periode', 'angkatan', 'status', 'idtagihan', 'user_id', 'totalnominal', 'satuan'
    ];

    protected $dates = ['deleted_at'];

    public function detail(){
    	return $this->hasMany('App\Detailbilling');    }
}
