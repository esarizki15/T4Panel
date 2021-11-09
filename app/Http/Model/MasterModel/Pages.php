<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'page_id';
    protected $table = 'page';

 	 protected $fillable = [ 'page_nm' ,'page_title' ,'page_isguest' ,'page_description' ,'page_keywords' ,'page_content'  ];
   
}
