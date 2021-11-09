<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerMarket extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bm_id';
    protected $table = 'banner_market';

 	 protected $fillable = ['bm_status','bm_link','bm_judul', 'bm_file'];
   
}
