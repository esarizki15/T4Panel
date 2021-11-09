<?php

namespace App\Http\Model\AppointmentModel;

use Illuminate\Database\Eloquent\Model;

class AppointmentStatusModel extends Model
{
    protected $connection = 'pgsql_main';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'appointment_status';

	protected $fillable = [ 'name','show','image_url','client_type','is_active','message','use_cluster','use_service','use_email' ];

}
