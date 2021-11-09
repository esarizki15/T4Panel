<?php

namespace App\Http\Controllers\MarketingBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

use App\Http\Model\MemberModel\Member;
use App\Http\Model\MarketingModel\PushNotification;
class PushNotificationController extends ScafoldController
{
   
    public function __construct(){

        $model = new PushNotification;
        $this->setModelController($model);

        $this->setTypeOfField("push_notification_id","hidden");
        $this->setTypeOfField("push_notification_text","text","content");
        $this->setTypeOfField("push_notification_tgl","hidden");
        $this->setTypeOfField("push_notification_title","text","title");
        $this->setTypeOfField("push_notification_url","text","url_to_open");
        $this->setTypeOfField("push_notification_url_host","hidden");
        $this->setTypeOfField("push_notification_type","hidden");
        // $this->setTypeOfField("push_notification_token","text","token");
        $this->setTypeOfField("push_notification_response","text","response");
        $this->setTypeOfField("id_tagihan","hidden");
        $this->setTypeOfField("push_notification_image","text","image_url");

        $member =  Member::select("member_id","member_nm","member_token_push")->WhereRaw('member_primary_account = 1 and member_status <> 9 and member_token_push is not null ')->get();
        $memberRemap = [];
        $memberRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemap[]=["id"=>$val->member_id, "name" =>  $val->member_id . ' | ' . $val->member_nm  ];
        }
        $collection['member_id'] = json_decode(json_encode($memberRemap));


        $memberRemapToken = [];
        $memberRemapToken[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemapToken[]=["id"=>$val->member_token_push, "name" => $val->member_id . ' | ' . $val->member_nm  ];
        }
        $collection['push_notification_token'] = json_decode(json_encode($memberRemapToken));
        
        // $this->setFieldRelation("push_notification_token",json_decode(json_encode(["name"=> "push_token_name" , "type"=> "text", "data"=>[], "relations" => [ "member" , "member_name" ] ])));


        $this->setFieldRelation("member_name",json_decode(json_encode(["name"=> "member_name" , "type"=> "text", "data"=>[], "relations" => [ "member" , "member_nm" ] ])));
        $this->setFieldRelation("member_id",json_decode(json_encode(["name"=> "member_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("member",json_decode(json_encode(["name"=> "member" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setTypeOfField("id","hidden"); 

        $collection['push_notification_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "NOT SEND" ] , ["id"=>"1", "name"=> "SENT" ] , ["id"=>"2", "name"=> "FAILED" ] ]) );
    	$collection['push_notification_type']= json_decode( json_encode([ ["id"=>"1", "name"=> "SEND NOW" ] ]) );
        
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		
        $this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","push-notification")->first());
    }


     public function index(Request $request)
    {   
        $this->setTypeOfField("supplier_id","hidden");
        $param = $request->all();
        $user = \Auth::user();
        $grantType = $user->grantOption($user->role);
        $model = $this->getModelController(); 
        $primaryKey = $model->getKeyName();
        $model = $model::select('*');
        $items = $model->orderBy($primaryKey,"DESC");
        $assetsPath = $this->getUrlPath();
        $dateRangeField = $this->dateRangeField ;
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
                                        $strarr[] = $column->name.' = ? ' ;
                                        foreach($val as $v) {
                                            if($v->name==$param['q']) {
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

        // if(isset($param['q']) && $param['q'] !== '' ) {
            // $param['dtr'] = '';
        // }else{

            if(isset($param['dtr'])) {
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
                $exports = new \App\Http\Controllers\ScafoldExport;
                $exports->setItems($items);
                $exports->setColumns($columns);
                return \Excel::download($exports,  $caption->code."-".date("YmdHis").".xls");
        }
        return $this->view('marketing-bundle.push-notification.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType", "dateRangeField" ) );
    }

    public function createBatch(){
        $this->setTypeOfField("supplier_id","hidden");
        $caption = $this->getCaption();
        $connectionname = $this->getModelController()->getConnectionName();
        $columns = \DB::connection($connectionname)->select( \DB::connection($connectionname)->raw(' 
        select column_name as name, data_type as type , is_nullable as not_required , 
         CASE WHEN column_name =\'slug\' THEN \'disabled\'
            ELSE \'\'
        END as disabled 

        from INFORMATION_SCHEMA.COLUMNS where table_name =\''.$this->getModelController()->getTable().'\' '));

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
        // pr($columns);exit;
        return $this->view('marketing-bundle.push-notification.create-batch',compact('columns', "caption" ));
    }  

    public function storeBatch(Request $request)
    {
        $data = $request->all();
        $resp= [];
        $msg = "Batch Created";
        if( isset($data['member_id']) && count($data['member_id']) > 0 ) {

            $title = ( isset($data['title']) ? $data['title'] : '' );
            $content = ( isset($data['content']) ? $data['content'] : '' );
            $url = ( isset($data['url']) ? $data['url'] : '' );
            $image_url = ( isset($data['image_url']) ? $data['image_url'] : '' );
            $memberId  = [];
            foreach($data['member_id'] as $val){
                if( !in_array($val, ["ALL_RESIDENT","ALL_NON_RESIDENT","ALL_MERCHANT","ALL_USERS"] )  ) $memberId[]=$val;
            }
            if(in_array("ALL_RESIDENT",$data['member_id'])) {
                $memberData =  Member::select("member_id")->WhereRaw('member_primary_account = 1 and member_status <> 9 and member_token_push is not null and member_ipl is not null ')->get();
                if(count($memberData) > 0 ) {
                    foreach($memberData as $val) {
                        $memberId[] = $val->member_id;
                    }
                }
            }
            if(in_array("ALL_NON_RESIDENT",$data['member_id'])) {
                $memberData =  Member::select("member_id")->WhereRaw('member_primary_account = 1 and member_status <> 9 and member_token_push is not null and member_ipl is null ')->get();
                if(count($memberData) > 0 ) {
                    foreach($memberData as $val) {
                        $memberId[] = $val->member_id;
                    }
                }
            }
            if(in_array("ALL_MERCHANT",$data['member_id'])) {
                $memberData =  Member::select("member_id")->WhereRaw('member_primary_account = 1 and member_status <> 9 and member_token_push is not null and member_cro = 2 ')->get();
                if(count($memberData) > 0 ) {
                    foreach($memberData as $val) {
                        $memberId[] = $val->member_id;
                    }
                }
            }
            if(in_array("ALL_USERS",$data['member_id'])) {
                $memberData = Member::select("member_id")->WhereRaw('member_primary_account = 1 and member_status <> 9 and member_token_push is not null ')->get();
                if(count($memberData) > 0 ) {
                    foreach($memberData as $val) {
                        $memberId[] = $val->member_id;
                    }
                }
            }
            
            $data['member_id'] = array_unique($memberId);
            // pr($data['member_id']);exit;
            if( count($data['member_id']) > 0 ) {
                foreach($data['member_id'] as $member_id ){

                        $member = Member::where('member_id',$member_id)->first();
                        $token_push = "";
                        $response = false;
                        if($member) {
                            $token_push = $member->member_token_push;
                        }
                        if($token_push) {

                            $saveData['member_id'] = $member_id;
                            $saveData['push_notification_text'] = $content;
                            $saveData['push_notification_title'] = $title;
                            $saveData['push_notification_url'] = $url;
                            $saveData['push_notification_image'] = $image_url;
                            $saveData['push_notification_token'] = $token_push;

                            try{
                                $this->getModelController()::create($saveData);
                                $msg = ' added successfully';
                                $response = true;
                            }catch(\Illuminate\Database\QueryException  $e){
                                $msg = ' added failed '. $e->getMessage();
                            }
                        }else{
                            $msg = "token push not found";
                        }

                        $resp[] = [ "resp" => $response , "msg"=> $msg ];

                }
            }
        }else{
            $resp[] = [ "resp" => false , "msg"=> "empty" ];
        }
        $msg = "Batch Created";
        return redirect()->route( current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
       } 

}