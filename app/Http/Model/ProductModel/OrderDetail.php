<?php

namespace App\Http\Model\ProductModel;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'pd_id';
    protected $table = 'penjualan_detail';

 	 protected $fillable = [];
   
}
