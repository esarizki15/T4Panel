<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentScheduleModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_schedule';
	protected $fillable = [ "appointment_service_id","appointment_schedule_date","appointment_schedule_time","is_resident","status","quota","appointment_location_id" ];
	

}
