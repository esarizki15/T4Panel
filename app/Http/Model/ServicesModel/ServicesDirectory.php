<?php

namespace App\Http\Model\ServicesModel;

use Illuminate\Database\Eloquent\Model;

class ServicesDirectory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'services_id';
    protected $table = 'services_directory';

 	 protected $fillable = [ 'services_name','services_image','services_status','created_date','services_type','member_id','sc_id'];
   public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }
	 
	public function servicesCategory() {
        return $this->belongsTo('App\Http\Model\ServicesModel\ServicesCategory','sc_id','sc_id');
    }
}
