<?php

namespace App\Http\Model\VoucherModel;

use Illuminate\Database\Eloquent\Model;

class PointScenario extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'point_scenario';

 	 protected $fillable = [
		'id','ps_name','ps_point','ps_status','ps_is_recur','ps_convertion_rate','ps_type','ps_unique_entities'  ];
   
}

