<?php

namespace App\Http\Model\ForumModel;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'forum_id';
    protected $table = 'forum';

 	 protected $fillable = [  'forum_img','forum_nm','forum_desc','member_id','forum_status','forum_pinned', 'fk_id','kategori_id'  ];
    
 	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }

 	public function forum_category() {
        return $this->belongsTo('App\Http\Model\ForumModel\ForumCategory','fk_id','fk_id');
    }

 	public function category() {
        return $this->belongsTo('App\Http\Model\MasterModel\Category','kategori_id','kategori_id');
    }
}
