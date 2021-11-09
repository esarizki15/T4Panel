<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class LogSms extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'logsms_id';
    protected $table = 'log_sms';

 	 protected $fillable = [];
   
}
