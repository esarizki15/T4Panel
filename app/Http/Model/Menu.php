<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $connection = 'mysql';
    public $timestamps = false;
    
    protected $fillable = [ 'name',  'code',  'status' , 'nav_id' ,'icon' ,'order'];

    public function submenu(){
        return $this->hasMany('App\Http\Model\SubMenu','menu_id','id')->orderBy('order','asc');
	}
	 public function navi()
    {
        return $this->belongsTo('App\Http\Model\Navigation','nav_id','id');
    }

}
