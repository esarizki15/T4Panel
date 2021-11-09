<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    //
    // protected $connection = 'pgsql_user_auth';
    public $timestamps = false;
    
    protected $fillable = [ 'menu_id' , 'name',  'code',  'status', 'path' ,'order' ];


    public function menu(){
        return $this->belongsTo('App\Http\Model\Menu','menu_id','id');

    }

}
