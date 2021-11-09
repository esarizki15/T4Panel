<?php

namespace App\Http\Model\ProductModel;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'produk_id';
    protected $table = 'produk';

 	 protected $fillable = [ 'member_id','produk_nm','kp_id','produk_date','produk_tags','produk_harga','produk_berat','produk_keterangan','produk_show','produk_stok','produk_status','produk_diskon','produk_panjang','produk_lebar','produk_tinggi','produk_seo'  ];

  	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }

    public function category() {
        return $this->belongsTo('App\Http\Model\MasterModel\CategoryProduct','kp_id','kp_id');
    }
   
}
