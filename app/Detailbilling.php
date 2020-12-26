<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detailbilling extends Model
{
    
    use SoftDeletes;

    protected $fillable = [
        'billings_id', 'kodedetailtagihan', 'deskripsipendek', 'deskripsipanjang', 'nominal'
    ];
    protected $dates = ['deleted_at'];
    public function billings(){
    	return $this->belongsTo('App\Billing');
    }
}
