<?php

namespace App\Http\Model\MarketingModel;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'push_notification_id';
    protected $table = 'push_notification';

 	 protected $fillable = [ 
		'member_id','push_notification_text','push_notification_status','push_notification_title','push_notification_type','push_notification_token','push_notification_response','id_tagihan','push_notification_url','push_notification_image'
 	 ];
   	public function member() {
        return $this->belongsTo('App\Http\Model\MemberModel\Member','member_id','member_id');
    }
 	

}
