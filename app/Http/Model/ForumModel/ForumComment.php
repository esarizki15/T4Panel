<?php

namespace App\Http\Model\ForumModel;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'fc_id';
    protected $table = 'forum_comment';

 	 protected $fillable = [  'fc_date','forum_id','member_id','fc_last_update','fc_isi'  ];
   	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }

  	public function forum() {
        return $this->belongsTo('App\Http\Model\ForumModel\Forum','forum_id','forum_id');
    }
}
