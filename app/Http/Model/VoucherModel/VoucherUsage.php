<?php

namespace App\Http\Model\VoucherModel;

use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'voucher_usage';

 	 protected $fillable = [ 
			'id','voucher_id','member_id','created_date','penjualan_id'
 	 ];
   
}

