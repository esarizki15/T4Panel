<?php

namespace App\Http\Model\ContentModel;

use Illuminate\Database\Eloquent\Model;

class BusinessDirectory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bd_id';
    protected $table = 'business_directory';

 	 protected $fillable = [ 'bd_nm','bd_status','bd_desc','bdc_id','bd_address','bd_phone','bd_hari','bd_jam_open','bd_long','bd_lat','bd_image', 'bd_merchant_id','member_id','bd_sosmed','bd_hari_note','bd_jam_close','bd_pic','bd_jam_note','bd_note','bd_date_approve','bd_image_ktp','bd_image_selfie','bd_image_atmosphere','bd_image_additional','bd_image_sign','bd_type'];
   
}
