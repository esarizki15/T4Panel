<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerHome extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bh_id';
    protected $table = 'banner_home';

 	 protected $fillable = [ 'bh_judul','bh_link','bh_file','bh_status' ];
   
}
