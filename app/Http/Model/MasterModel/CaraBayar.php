<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class CaraBayar extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'cb_id';
    protected $table = 'cara_bayar';

 	 protected $fillable = [ 'cb_nm' ,'cb_status' ,'cb_ket' ,'cb_logo' ,'cb_prefix' ];
   
}
