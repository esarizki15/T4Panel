<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerMarketCategory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bmc_id';
    protected $table = 'banner_market_category';

 	 protected $fillable = ['bmc_status','bmc_link','bmc_judul', 'bmc_file'];
   
}
