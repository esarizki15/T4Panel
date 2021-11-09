<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{

    // protected $connection = 'pgsql_user_auth';
    public $timestamps = false;
    
    protected $table = 'user_role';
 
    protected $fillable = ['id','code','view','create','update','delete'];

   
}
