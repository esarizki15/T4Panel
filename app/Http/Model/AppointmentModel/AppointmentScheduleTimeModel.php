<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentScheduleTimeModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_schedule_time';
	protected $fillable = [ "time_start", "time_end", "quota", "appointment_location_id" ];
	

}
