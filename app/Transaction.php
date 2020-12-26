<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
	use SoftDeletes;
    protected $fillable = [
		'id', 'billing_id', 'nama', 'kodebank', 'kodechannel', 'kodeterminal', 'nomorpembayaran', 'tanggaltransaksi', 
		'idtransaksi', 'totalnominal', 'nomorjurnalpembukuan', 'checksum', 'userid', 'choices'
    ];
	protected $dates = ['deleted_at'];
	
}
