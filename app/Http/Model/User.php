<?php

namespace App\Http\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
class User extends Authenticatable
{
    use Notifiable;
    // protected $connection = 'pgsql_user_auth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'fullname' , 'username' , 'role_code' , 'organization_id', 'branch_id', 'division_id', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function accessmenu(){
        return $this->hasMany('App\Http\Model\AclMenu','user_id','id');
    }
    public function role(){
       return  $this->belongsTo('App\Http\Model\UserRole','role_code','code');
       
    }
    
    public function grantOption($role=null){
        $grantOption['VIEW'] = 0;
        $grantOption['CREATE'] = 0;
        $grantOption['UPDATE'] = 0;
        $grantOption['DELETE'] = 0;
        $grantOption['SUPER'] = 0;
         if(isset($role)) {
                    $grantOption['VIEW'] = $role->view;
                    $grantOption['CREATE'] = $role->create;
                    $grantOption['UPDATE'] = $role->update;
                    $grantOption['DELETE'] = $role->delete;
                    $grantOption['SUPER'] = $role->super;
        }
        return json_decode(json_encode($grantOption));
    }
}
