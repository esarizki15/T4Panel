<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerForum extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bf_id';
    protected $table = 'banner_forum';

 	 protected $fillable = ['bf_status','bf_link','bf_judul', 'bf_file'];
   
}
