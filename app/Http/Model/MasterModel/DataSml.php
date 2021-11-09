<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class DataSml extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'data_sml';

 	 protected $fillable = [ 'name','email','password','address','project_name','cluster_name','project_cluster_code','res_number','phone','status'];
   
}
