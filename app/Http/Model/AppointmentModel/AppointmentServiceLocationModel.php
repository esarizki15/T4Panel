<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentServiceLocationModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_service_location';
	protected $fillable = ["location_name","status","longitude","latitude","client_type","cluster_id","service_id"];


}
