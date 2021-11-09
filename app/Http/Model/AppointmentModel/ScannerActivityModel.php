<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class ScannerActivityModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'scanner_activity';
	protected $fillable = [ 'member_id','created_date','event_at','location_id','cluster_id','pic_id','phone_number','type'
    ];
}
