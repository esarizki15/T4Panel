<?php

namespace App\Http\Model\MemberModel;

use Illuminate\Database\Eloquent\Model;

class MemberIpl extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'member_ipl';

 	 protected $fillable = ['member_id','ipl'];
   	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }
}
