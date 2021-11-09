<?php

namespace App\Http\Model\VoucherModel;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'voucher';

 	 protected $fillable = [ 
		'id','v_name','v_code','amount','term','need_point','status','start_date','end_date','member_id','description','amount_type','term_and_condition','how_to_use','type','category','image'
 	 ];
   	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }
 
}
