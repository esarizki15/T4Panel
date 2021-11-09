<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'mp_id';
    protected $table = 'metode_pembayaran';

 	 protected $fillable = [ 'mp_nm','mp_status','mp_tipe','mp_kd','mp_ket','mp_logo' ];
   
}
