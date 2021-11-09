<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentServicesModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_services';
	protected $fillable = ["service_name","status","service_image","service_desc","service_day","days","min_days","service_order","client_type","show_date","show_time","show_location","service_quota","location_id","show_share","show_qr_code","show_reschedule","show_cancel","show_upload","upload_label","show_service","show_description","member_id","price","discount","service_code","commision","duration","commision_share"];
}
