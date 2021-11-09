<?php

namespace App\Http\Model\MemberModel;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'member_id';
    protected $table = 'member';

 	 protected $fillable = [ 'member_username','member_nm','member_email','member_phone','member_authkey','member_password','member_status',  'member_password_token','bank_id','member_account_no','member_account_nm','member_sponsor','member_downline','member_network','member_no_id','member_tipe_id','city_id','member_addr','member_pin','member_ticket','member_level','member_upline','member_partner','member_cn','member_cro','member_ahli_waris','member_hub_ahli_waris','member_left','member_right','member_pos','member_master','member_parent','member_bonus','member_type','member_activated','member_omzet_left','member_omzet_right','member_unique_number','member_free','country_id','member_bitcoin','member_sp','member_sw','member_ss','member_activated2','member_sw_expired','member_ktp','member_kk','member_block_wd','member_shu','member_refund','member_block','member_telegram_id','member_pin_salah','member_token_push','member_password_firebase','member_selisih','member_nm2','member_lahir','member_photo','facebook_id','facebook_token','member_ref','member_network2','member_ipl','member_cluster','member_project','kategori_id','subdistrict_id','member_alamat','member_dob','member_gender','member_hobi','member_primary_account','member_str'];
   
}
