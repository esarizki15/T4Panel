<?php

namespace App\Http\Model\ServicesModel;

use Illuminate\Database\Eloquent\Model;

class ServicesSubProduct extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'ssp_id';
    protected $table = 'services_sub_product';

 	 protected $fillable = [ 'ssp_name','ssp_image','ssp_status','ssp_price','services_id','member_id','sc_id','sp_id' ];
	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }
	public function servicesProduct() {
        return $this->belongsTo('App\Http\Model\ServicesModel\ServicesProduct','sp_id','sp_id');
    }
	public function servicesCategory() {
        return $this->belongsTo('App\Http\Model\ServicesModel\ServicesCategory','sc_id','sc_id');
    }
	public function servicesDirectory() {
        return $this->belongsTo('App\Http\Model\ServicesModel\ServicesDirectory','services_id','services_id');
    }
}
