<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'kp_id';
    protected $table = 'kategori_produk';

 	 protected $fillable = [ 'kp_nm' ,'kp_gbr' , 'kp_status' ];
   
}
