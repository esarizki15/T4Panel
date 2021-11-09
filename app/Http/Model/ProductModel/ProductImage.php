<?php

namespace App\Http\Model\ProductModel;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'pg_id';
    protected $table = 'produk_gambar';

 	 protected $fillable = ['pg_nm','produk_id'];
    
     public function product() {
        return $this->belongsTo('App\Http\Model\ProductModel\Product','produk_id','produk_id');
    }
}
