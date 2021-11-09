<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerCustomerService extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bmp_id';
    protected $table = 'banner_customer_service';

 	 protected $fillable = ['bmp_status','bmp_link','bmp_judul', 'bmp_file'];
   
}
