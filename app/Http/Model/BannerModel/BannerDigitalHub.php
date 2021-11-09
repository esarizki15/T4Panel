<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerDigitalHub extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bmp_id';
    protected $table = 'banner_digital_hub';

 	 protected $fillable = ['bmp_status','bmp_link','bmp_judul', 'bmp_file'];
   
}
