<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentMemberModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_member';

	protected $fillable = [ "appointment_schedule_id","total_customer","appointment_code","appointment_date","gate_in","gate_out","received_by","handling_start","handling_end","status","member_id","service_id","location_id","picked_date","picked_time","member_address","identity_code","appointment_description","sub_service_id","handling_by_panel","notes" ,"client_type","reschedule_date_to","reschedule_time_to"];


}
