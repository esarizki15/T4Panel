<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentSubServicesModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_sub_services';
	protected $fillable = ["sub_service_name","service_id","text_box","status"];
	 
}
