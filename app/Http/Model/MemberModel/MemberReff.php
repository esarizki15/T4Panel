<?php

namespace App\Http\Model\MemberModel;

use Illuminate\Database\Eloquent\Model;

class MemberReff extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'mr_id';
    protected $table = 'member_reff';

 	protected $fillable = [ 'member_id','mr_code','mr_status','member_id2','mr_date' ];
   
}
