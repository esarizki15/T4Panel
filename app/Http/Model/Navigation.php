<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    //
    // protected $connection = 'pgsql_user_auth';
    public $timestamps = false;
    
    protected $fillable = [ 'name',  'code' ,'order' ];


    public function menu(){
        return $this->hasMany('App\Http\Model\Menu','nav_id','id')->orderBy('order','asc');
	}

}
