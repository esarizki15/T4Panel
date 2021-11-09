<?php

namespace App\Http\Model\ContentModel;

use Illuminate\Database\Eloquent\Model;

class BusinessDirectoryCategory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'bdc_id';
    protected $table = 'business_directory_category';

 	 protected $fillable = ['bdc_nm','bdc_status'];
   
}
