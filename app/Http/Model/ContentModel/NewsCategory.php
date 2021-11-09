<?php

namespace App\Http\Model\ContentModel;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{

  	protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'news_category';

 	 protected $fillable = [ 
		'id','name','status'
 	 ];
   
}
