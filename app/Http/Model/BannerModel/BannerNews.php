<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerNews extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bn_id';
    protected $table = 'banner_news';

 	 protected $fillable = ['bn_status','bn_link','bn_judul', 'bn_file'];
   
}
