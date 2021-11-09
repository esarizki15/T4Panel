<?php

namespace App\Http\Model\ServicesModel;

use Illuminate\Database\Eloquent\Model;

class ServicesCategory extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'sc_id';
    protected $table = 'services_category';

 	 protected $fillable = [ 'sc_name','sc_status','sc_image','sc_desc','sc_has_subs','sc_type','sc_has_owner' ];
   
}
