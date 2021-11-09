<?php

namespace App\Http\Model\ForumModel;

use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'fk_id';
    protected $table = 'forum_kategori';

 	 protected $fillable = [ 'fk_nm','fk_status' ];
   
}
