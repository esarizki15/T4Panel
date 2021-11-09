<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class AclMenu extends Model
{

    // protected $connection = 'pgsql_user_auth';
    public $timestamps = false;
    
    protected $fillable = [ 'user_id' ,'menu_id' ,'sub_menu_id' ];

}
