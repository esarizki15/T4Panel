<?php

namespace App\Http\Model\ProductModel;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'order_id';
    protected $table = 'order';

 	 protected $fillable = [];
   
}
