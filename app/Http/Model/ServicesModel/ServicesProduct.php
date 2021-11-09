<?php

namespace App\Http\Model\ServicesModel;

use Illuminate\Database\Eloquent\Model;

class ServicesProduct extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'sp_id';
    protected $table = 'services_product';

 	 protected $fillable = [ 'sp_name','sp_image','sp_status','sp_price','services_id','member_id','sc_id','pay_able'];
 	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }
	 
	public function servicesCategory() {
        return $this->belongsTo('App\Http\Model\ServicesModel\ServicesCategory','sc_id','sc_id');
    }
	public function servicesDirectory() {
        return $this->belongsTo('App\Http\Model\ServicesModel\ServicesDirectory','services_id','services_id');
    }
}
