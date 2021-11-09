<?php

namespace App\Http\Controllers\VisitorBundle;

use Illuminate\Http\Request;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\AppointmentServicesModel;
class ServicesController extends ScafoldController
{
    var $moduleName = "visitor";
    var $clientType = "VISITOR";
    var $clientTypelabel = "ADVISOR";

    public function __construct(){
        $model = new AppointmentServicesModel;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        $this->setTypeOfField("location_id","hidden");
        $this->setTypeOfField("service_day","hidden");
        $this->setTypeOfField("member_id",null,"advisor");
        $this->setTypeOfField("service_code",null,"service");

        $this->setTypeOfField("service_image","hidden");
        $this->setTypeOfField("show_location","hidden");
        $this->setTypeOfField("service_desc","hidden");
        $this->setTypeOfField("service_order","hidden");
        $this->setTypeOfField("show_share","hidden");
        $this->setTypeOfField("show_qr_code","hidden");
        $this->setTypeOfField("show_service","hidden");
        $this->setTypeOfField("show_upload","hidden");
        $this->setTypeOfField("upload_label","hidden");
        $this->setTypeOfField("show_description","hidden");
        $this->setTypeOfField("show_time","hidden");
        $this->setTypeOfField("show_date","hidden");
        $this->setTypeOfField("show_reschedule","hidden");
        $this->setTypeOfField("show_cancel","hidden");
        $this->setTypeOfField("commision_share","hidden");

        $collection['show_time']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_date']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_location']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_share']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_qr_code']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_upload']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_reschedule']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_cancel']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_service']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );
        $collection['show_description']= json_decode( json_encode([ ["id"=>"0", "name"=> "HIDE" ] , ["id"=>"1", "name"=> "SHOW" ]  ]) );        
        $collection['service_code']= json_decode( json_encode([ ["id"=>"CHAT", "name"=> "Chat Service" ] , ["id"=>"VIDEO_CALL", "name"=> "Video Call Service" ]  ]) );        
        // $this->setTypeOfField("show_date","hidden");
        // $this->setTypeOfField("show_time","hidden");
        // $this->setTypeOfField("show_location","hidden");
        $this->setTypeOfField("service_quota","hidden");
        $collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
        $collection['client_type']= json_decode( json_encode([ ["id"=>"$this->clientType", "name"=> "{$this->clientTypelabel}" ] ]) );
        $member =  \App\Http\Model\MemberModel\Member::select("member_id","member_nm")->WhereRaw('member_status <> 9 and member_type= 2')->get();
        $memberRemap = [];
        $memberRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemap[]=["id"=>$val->member_id, "name" =>  $val->member_nm  ];
        }
        $collection['member_id'] = json_decode(json_encode($memberRemap));

        $this->setCollections($collection);
       	$this->setStoragePath(  env('CATEGORY_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('CATEGORY_ASSETS_PATH','') );
       	$this->setThumbWidth(0);
        $this->setThumbHeight(0);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","{$this->moduleName}-services")->first());
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
        $model->where('client_type', '=', $this->clientType);
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
                       if(in_array($column->type, [ "date" ] ) ){
                            $strarr[] = $column->name.' = ? ' ;
                            $valarr[] = $param['q'];
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
                if( count($strarr) >0 && $param['q'] && count($valarr) > 0 ) {
                    $items = $model->WhereRaw(implode(" OR ",$strarr),$valarr)->orderBy($primaryKey,"DESC");
                } 
            }
        }else{
            $param['q'] = '';
        }

        // if(isset($param['q']) && $param['q'] !== '' ) {
            // $param['dtr'] = '';
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
                $exports = new \App\Http\Controllers\ScafoldExport;
                $exports->setItems($items);
                $exports->setColumns($columns);
                return \Excel::download($exports,  $caption->code."-".date("YmdHis").".xls");
        }
        return $this->view('scafold-bundle.scafolding.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType", "dateRangeField" ) );
    }
}
