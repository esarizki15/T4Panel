<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerBusinessDirectory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bbd_id';
    protected $table = 'banner_business_directory';

 	 protected $fillable = ['bbd_status','bbd_link','bbd_judul', 'bbd_file'];
   
}
