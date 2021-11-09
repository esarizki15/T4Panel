<?php

namespace App\Http\Model\VoucherModel;

use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'point_history';

 	 protected $fillable = [ 'id','ph_name','member_id','ps_id','created_date','ph_before_point','ph_after_point','entities_id','entities_type' ];
   	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }
}

