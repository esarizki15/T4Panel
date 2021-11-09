<?php

namespace App\Http\Model\BannerModel;

use Illuminate\Database\Eloquent\Model;

class BannerForumThread extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bft_id';
    protected $table = 'banner_forum_thread';

 	 protected $fillable = ['bft_status','bft_link','bft_judul', 'bft_file'];
   
}
