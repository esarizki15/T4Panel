<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'setting_id';
    protected $table = 'setting';

 	 protected $fillable = [

			'setting_nm',
			'setting_val_str',
			'setting_val_int',
			'setting_val_float',
			'setting_input',
			'setting_desc'
	];
   
}
