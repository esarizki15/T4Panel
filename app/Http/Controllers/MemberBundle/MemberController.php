<?php

namespace App\Http\Controllers\MemberBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MemberModel\Member;
use App\Http\Model\MasterModel\DataSml;
class MemberController extends ScafoldController
{
   
    
    public function __construct(){

        $model = new Member;
        $this->setModelController($model);
        $this->setTypeOfField('member_id',"label");
        $this->setTypeOfField('member_authkey',"hidden");
        $this->setTypeOfField('member_password',"hidden");
        $this->setTypeOfField('member_created',"label","registration_date");
        $this->setTypeOfField('member_updated',"hidden");
        $this->setTypeOfField('member_password_token',"hidden");
        $this->setTypeOfField('bank_id',"hidden");
        $this->setTypeOfField('member_account_no',"hidden");
        $this->setTypeOfField('member_account_nm',"hidden");
        $this->setTypeOfField('member_sponsor',"hidden");
        $this->setTypeOfField('member_downline',"hidden");
        $this->setTypeOfField('member_network',"hidden");
        $this->setTypeOfField('member_no_id',"hidden");
        $this->setTypeOfField('member_tipe_id',"hidden");
        $this->setTypeOfField('city_id',"hidden");
        $this->setTypeOfField('member_pin',"hidden");
        $this->setTypeOfField('member_ticket',"hidden");
        $this->setTypeOfField('member_level',"hidden");
        $this->setTypeOfField('member_upline',"hidden");
        $this->setTypeOfField('member_partner',"hidden");
        $this->setTypeOfField('member_cn',"hidden");
        $this->setTypeOfField('member_ahli_waris',"hidden");
        $this->setTypeOfField('member_hub_ahli_waris',"hidden");
        $this->setTypeOfField('member_left',"hidden");
        $this->setTypeOfField('member_right',"hidden");
        $this->setTypeOfField('member_pos',"hidden");
        $this->setTypeOfField('member_master',"hidden");
        $this->setTypeOfField('member_parent',"hidden");
        $this->setTypeOfField('member_bonus',"hidden");
        $this->setTypeOfField('member_activated',"hidden");
        $this->setTypeOfField('member_omzet_left',"hidden");
        $this->setTypeOfField('member_omzet_right',"hidden");
        $this->setTypeOfField('member_unique_number',"hidden");
        $this->setTypeOfField('member_free',"hidden");
        $this->setTypeOfField('country_id',"hidden");
        $this->setTypeOfField('member_bitcoin',"hidden");
        $this->setTypeOfField('member_sp',"hidden");
        $this->setTypeOfField('member_sw',"hidden");
        $this->setTypeOfField('member_activated2',"hidden");
        $this->setTypeOfField('member_sw_expired',"hidden");
        $this->setTypeOfField('member_ktp',"text","ID Card Number");
        $this->setTypeOfField('member_kk',"hidden");
        $this->setTypeOfField('member_block_wd',"hidden");
        $this->setTypeOfField('member_shu',"hidden");
        $this->setTypeOfField('member_refund',"hidden");
        $this->setTypeOfField('member_block',"hidden");
        $this->setTypeOfField('member_telegram_id',"hidden");
        $this->setTypeOfField('member_pin_salah',"hidden");
        $this->setTypeOfField('member_token_push',"hidden");
        $this->setTypeOfField('member_password_firebase',"hidden");
        $this->setTypeOfField('member_selisih',"hidden");
        $this->setTypeOfField('member_nm2',"hidden");
        $this->setTypeOfField('member_lahir',"hidden");
        $this->setTypeOfField('facebook_id',"hidden");
        $this->setTypeOfField('facebook_token',"hidden");
        $this->setTypeOfField('member_ref',"hidden");
        $this->setTypeOfField('member_network2',"hidden");
        $this->setTypeOfField('kategori_id',null,"specialist");
        $this->setTypeOfField('subdistrict_id',"hidden");
        $this->setTypeOfField('member_alamat',"hidden");

        $this->setTypeOfField('member_username',"text","username");
        $this->setTypeOfField('member_nm',"text","fullname");
        $this->setTypeOfField('member_email',"text","email");
        $this->setTypeOfField('member_phone',"text","phone_number");
        // $this->setTypeOfField('member_ipl',"text","ipl_code");
        // $this->setTypeOfField('member_cluster',"text","cluster");
        // $this->setTypeOfField('member_project',"text","project");

        $this->setTypeOfField('member_ipl',"hidden");
        $this->setTypeOfField('member_cluster',"hidden");
        $this->setTypeOfField('member_project',"hidden");
        $this->setTypeOfField('member_status',null,"status");
        $this->setTypeOfField('member_addr',"text","address");
        $this->setTypeOfField('member_cro',"hidden");
        $this->setTypeOfField('member_type',null,"user_type");
        // $this->setTypeOfField('member_ss',"hidden");
        $this->setTypeOfField('member_ss',null,"Staff");
        $this->setTypeOfField('member_photo',"file","photo");
        $this->setTypeOfField('member_dob',"text-datepicker","date_of_birth");
        $this->setTypeOfField('member_gender',null,"gender");
        $this->setTypeOfField('member_hobi',"text","hobby");
        $this->setTypeOfField('member_primary_account',"hidden");
        $this->setTypeOfField('member_network',null,"aktivasi_dengan");


        $this->setStoragePath(  env('MEMBER_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('MEMBER_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);
        
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['member_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "DISABLED" ] , ["id"=>"1", "name"=> "ENABLED" ]  ]) );
		$collection['member_ss']= json_decode( json_encode([ ["id"=>"0", "name"=> "Not Staff" ] ,  ["id"=>"1", "name"=> "Advisor" ], ["id"=>"2", "name"=> "Theraphist" ]  ]) );
		$collection['member_type']= json_decode( json_encode([ ["id"=>"0", "name"=> "CHOOSE" ] , ["id"=>"1", "name"=> "iGlow Customer" ], ["id"=>"2", "name"=> "iGlow Staff" ]  ]) );
        $collection['member_gender']= json_decode( json_encode([ ["id"=>"0", "name"=> "CHOOSE" ], ["id"=>"1", "name"=> "Pria" ] , ["id"=>"2", "name"=> "Wanita" ]  ]) );
		$collection['member_primary_account']= json_decode( json_encode([ ["id"=>"0", "name"=> "NO" ] , ["id"=>"1", "name"=> "YES" ]  ]) );
        $collection['member_network']= json_decode( json_encode([ ["id"=>"0", "name"=> "CHOOSE" ] , ["id"=>"1", "name"=> "IPL" ], ["id"=>"1.1", "name"=> "KODE KELUARGA" ]  ]) );

        $this->setDateRangeField("member_created");
	   	// $memberIpl =  DataSml::select("phone","res_number")->whereNotNull("phone")->limit(10)->get();
     //    $memberIplRemap = [];
     //    $memberIplRemap[] = ["id"=>null,"name"=>"CHOOSE"];
     //    foreach($memberIpl as $val){
     //        $memberIplRemap[]=["id"=>$val->res_number, "name" =>  $val->res_number . " | " . $val->phone ];
     //    }
     //    $collection['member_ipl'] = json_decode(json_encode($memberIplRemap));

        $category =  \App\Http\Model\MasterModel\Category::select("kategori_id","kategori_nm")->get();
        $categoryRemap = [];
        $categoryRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($category as $val){
            $categoryRemap[]=["id"=>$val->kategori_id, "name" =>  $val->kategori_nm  ];
        }
        $collection['kategori_id'] = json_decode(json_encode($categoryRemap));

		$this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","member")->first());
    }

    public function index(Request $request)
    {   
        $this->setTypeOfField("supplier_id","hidden");
        $param = $request->all();
        $user = \Auth::user();
        $grantType = $user->grantOption($user->role);
        $model = $this->getModelController(); 
        $dateRangeField = $this->dateRangeField ;
        $primaryKey = $model->getKeyName();
        $model = $model::select(['*',  \DB::raw("to_char(member.member_created , 'YYYY-MM-DD')as member_created")]);
         $model->WhereRaw('member_status <> 9 ');
        $items = $model->orderBy($primaryKey,"DESC");
        $assetsPath = $this->getUrlPath();
        $caption = $this->getCaption();
        $connectionname = $this->getModelController()->getConnectionName();
        $columns = \DB::connection($connectionname)->select( \DB::connection($connectionname)->raw(' 
                select column_name as name, data_type as type 
                from INFORMATION_SCHEMA.COLUMNS where table_name =\''.$this->getModelController()->getTable().'\' group by column_name, data_type '));
        if(isset($param['fbfl'])) {
            $fbfl = $param['fbfl'];
        }else{
            $fbfl = '';
        }

        if(isset($param['q'])) {
            $strarr= [];
            $valarr= [];
            if(count($columns)>0){
                foreach($columns as $column){
                    if( $fbfl == $column->name ) {
                        if(in_array($column->type, [ "character varying" , "text"] ) ){
                            $strarr[] = 'LOWER('.$column->name.') LIKE ? ' ;
                            $valarr[] = '%' . strtolower($param['q']) . '%';
                        }
                        if($this->itemCollection){
                            foreach($this->itemCollection as $key => $val){
                                 if($column->name==$key) {
                                        foreach($val as $v) {
                                            if($v->name==$param['q']) {
                                                $strarr[] = $column->name.' = ? ' ;
                                                $valarr[] = $v->id;
                                            }
                                        }
                                 }
                            }
                        }
                    }

                }   
             if( count($strarr) >0 && $param['q']) {
                $items = $model->WhereRaw(implode(" OR ",$strarr),$valarr)->orderBy($primaryKey,"DESC");

                }

            }
        }else{
            $param['q'] = '';
        }

        // if(isset($param['q']) && $param['q'] !== ''  ) {
        //     $param['dtr'] = '';
        // }else{

            if(isset($param['dtr']) && isset( $param['search-by-date'] ) ) {
                if( $this->dateRangeField ) {
                        
                    $dateRangeParamValueStart=date("Y-m-d");
                    $dateRangeParamValueEnd=date("Y-m-d");
                    $dateRangeFullRaw = explode(" - ",$param['dtr']);
                    if( count($dateRangeFullRaw) > 1) {
                        $dateRangeParamRawStart = explode("/",$dateRangeFullRaw[0]);
                        $dateRangeParamRawEnd = explode("/",$dateRangeFullRaw[1]);
                        $dateRangeParamPartStart[] = (isset($dateRangeParamRawStart[2]) ? $dateRangeParamRawStart[2] : "" );
                        $dateRangeParamPartStart[] = (isset($dateRangeParamRawStart[0]) ? $dateRangeParamRawStart[0] : "" );
                        $dateRangeParamPartStart[] = (isset($dateRangeParamRawStart[1]) ? $dateRangeParamRawStart[1] : "" );

                        $dateRangeParamPartEnd[] = (isset($dateRangeParamRawEnd[2]) ? $dateRangeParamRawEnd[2] : "" );
                        $dateRangeParamPartEnd[] = (isset($dateRangeParamRawEnd[0]) ? $dateRangeParamRawEnd[0] : "" );
                        $dateRangeParamPartEnd[] = (isset($dateRangeParamRawEnd[1]) ? $dateRangeParamRawEnd[1] : "" );

                        $useDateRangeValue = true;
                        foreach($dateRangeParamPartStart as $val){
                            if($val=="") {
                                $useDateRangeValue = false;
                            }
                        }
                        foreach($dateRangeParamPartEnd as $val){
                            if($val=="") {
                                $useDateRangeValue = false;
                            }
                        }
                        if($useDateRangeValue) {
                            $dateRangeParamValueStart = implode("-", $dateRangeParamPartStart)." 00:00:00";
                            $dateRangeParamValueEnd = implode("-", $dateRangeParamPartEnd)." 23:59:59";
                            $model = $model->where($this->dateRangeField,">=",$dateRangeParamValueStart)->where($this->dateRangeField,"<=",$dateRangeParamValueEnd);
                        }
                    } 
                }

            }else{
                $param['dtr'] = '';
            }
        // }

        if(isset($param['export'])) {
            $items = $items->get();
        }else{
            $items = $items->paginate(10);

        }
        if(isset($items) && count($items) >0 ){
            if($primaryKey != 'id' ) {
                foreach($items as &$item){
                    $item->id = $item->$primaryKey;
                }
            }
        }
        foreach($columns as &$column){
                $column->data = [];
                $column->type = "text";
                $column->label = $column->name;
                 if($this->itemCollection){
                    foreach($this->itemCollection as $key => $val){
                        if($column->name==$key) $column->data = $val;
                    }
                }
                if($this->typeOfFields){
                     foreach($this->typeOfFields as $key => $val){
                        if($column->name==$key) $column->type = $val; 
                    }
                }
                if($this->labelOfFields){
                     foreach($this->labelOfFields as $key => $val){
                        if($column->name==$key) $column->label = $val; 
                    }
                }
            }
        $columnArr = [] ;
        foreach($columns as $val){
            $columnArr[$val->name] = $val;
        }
        if($this->fieldRelation){
            foreach($items as &$item){
                foreach($this->fieldRelation as $val){
                    $objectName = $val->name;
                    $columnArr[$val->name] = $val;
                    if(count($val->relations) > 0 ) {
                        $tempItems = $item;
                        foreach($val->relations as $relation){
                            if(isset($tempItems->$relation) ) {
                              $tempItems= $tempItems->$relation;
                            }
                        }
                        if(!is_object($tempItems)) $item->$objectName = $tempItems;  
                        else $item->$objectName = null; 
                    }  
                    
                }
               
            }
        } 
        $columns = $columnArr;
        if(isset($param['export'])) {
                ini_set("memory_limit",-1);
                $exports = new \App\Http\Controllers\ScafoldExport;
                $exports->setItems($items);
                $exports->setColumns($columns);
                return \Excel::download($exports,  $caption->code."-".date("YmdHis").".xls");
        }
        return $this->view('scafold-bundle.scafolding.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType" ,"dateRangeField") );
    }

}