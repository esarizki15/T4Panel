<?php

namespace App\Http\Model\VoucherModel;

use Illuminate\Database\Eloquent\Model;

class VoucherCategory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'voucher_category';

 	 protected $fillable = [ 
		'id','name','status'
 	 ];
   
}
