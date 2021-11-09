<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentAdditionalDataModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_additional_data';
	protected $fillable = ['value','appointment_id','service_id','client_type','additional_form_id','idx'];
	 
}
