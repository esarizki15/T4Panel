<?php

namespace App\Http\Model\VoucherModel;

use Illuminate\Database\Eloquent\Model;

class VoucherPartner extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'voucher_partner';

 	 protected $fillable = [ 
		'id','name','image','category','address','voucher_id','bd_id'
 	 ];
   
}
