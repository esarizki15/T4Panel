<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentAdditionalFormModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_additional_form';
	protected $fillable = ['label','code','type','service_id','client_type','column_order','required','options','validation'];
	 

}
