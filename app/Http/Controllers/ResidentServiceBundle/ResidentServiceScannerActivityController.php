<?php

namespace App\Http\Controllers\ResidentServiceBundle;

use Illuminate\Http\Request;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\ScannerActivityModel;
class ResidentServiceScannerActivityController extends ScafoldController
{
    public function __construct(){
        $model = new ScannerActivityModel;
        $this->setModelController($model);
        $this->setDateRangeField("created_date");
        $this->setTypeOfField("id","hidden");
        $this->setCaption(\App\Http\Model\SubMenu::where("code","residentservice-scanner-activity")->first());
    }

    public function index(Request $request)
    {   
        
        $param = $request->all();
        $user = \Auth::user();
        $grantType = $user->grantOption($user->role);
        $model = $this->getModelController(); 
        $primaryKey = $model->getKeyName();
        $assetsPath = $this->getUrlPath();
        $dateRangeField = $this->dateRangeField ;
        $caption = $this->getCaption();
        $model = $model::select([
                \DB::raw(" scanner_activity.id   "),
                \DB::raw(" kategori.kategori_nm as cluster   "),
                // \DB::raw(" appointment_service_location.location_name as location   "),
                \DB::raw(" member.member_nm as resident_name   "),
                \DB::raw("case scanner_activity.event_at  
                when 'CLUSTER_GATE' then 'SCAN AT GATE' 
                when 'RESIDENTIAL' then 'OFFICER SCAN AT GATE' 
                else scanner_activity.event_at end as  event  "),
                \DB::raw(" scanner_activity.type   "),
                \DB::raw(" scanner_activity.created_date as date   "),
                \DB::raw(" member_pic.member_nm as officer_name   "),
                \DB::raw(" scanner_activity.phone_number   "),
            ]);
        $model->leftJoin('member','member.member_id','=','scanner_activity.member_id') ;
        $model->leftJoin('member as member_pic','member_pic.member_id','=','scanner_activity.pic_id') ;
        // $model->leftJoin('appointment_service_location','appointment_service_location.id','=','scanner_activity.location_id') ;
        $model->leftJoin('kategori','kategori.kategori_id','=','scanner_activity.cluster_id') ;
        $assetsPath = $this->getUrlPath();

        $caption = $this->getCaption();
        $columns = json_decode(json_encode([ 
            [ "name" => "id" , "type" => "character varying" , "table" => "" , "filter" => false ],
            [ "name" => "cluster" , "type" => "character varying" , "table" => "" , "where" => "kategori.kategori_nm" ],
            // [ "name" => "location" , "type" => "character varying" , "table" => "" , "where" => "appointment_service_location.location_name" ],
            [ "name" => "event" , "type" => "character varying" , "table" => "" ,  "where" => "
            case scanner_activity.event_at  
                when 'CLUSTER_GATE' then 'SCAN AT GATE' 
                when 'RESIDENTIAL' then 'OFFICER SCAN AT GATE' 
                else scanner_activity.event_at end " ],
            [ "name" => "type" , "type" => "character varying" , "table" => "",  "where" => "scanner_activity.type" ],
            [ "name" => "date" , "type" => "character varying" , "table" => "" , "filter" => false ],
            [ "name" => "resident_name" , "type" => "character varying" , "table" => "" , "where" => "member.member_nm" ],
            [ "name" => "officer_name" , "type" => "character varying" , "table" => "" , "where" => "member_pic.member_nm" ],
            [ "name" => "phone_number" , "type" => "character varying" , "table" => "" ],
            
        ]));

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

                    if(in_array($column->type, [ "character varying" , "text"] ) ){
                        if( $fbfl == $column->name ) {
                            if( isset($column->where) && $column->where ) {
                                $strarr[] =  'LOWER('.$column->where.') LIKE ? ' ;
                            }else{
                                $strarr[] = ( ( isset($column->table ) && $column->table )  ? 'LOWER('.$column->table.'.'.$column->name.') LIKE ? ' : 'LOWER('.$column->name.') LIKE ? ' ) ;
                            }
                            $valarr[] = '%' . strtolower($param['q']) . '%';
                        }
                    } else if (in_array($column->type, ["int"])) {
                        if( $fbfl == $column->name ) {
                            if( isset($column->where) && $column->where ) {
                                $strarr[] = "{$column->where} = ?";
                            }else{
                                $strarr[] = ( ( isset($column->table ) && $column->table )  ? "{$column->table}.{$column->name} = ?" : "{$column->name} = ?" ) ;
                            }

                            $val_temp = null;
                            if (isset($column->dict)){
                                foreach($column->dict as $key => $value){
                                    if( stristr( $value, $param['q'] ) ){
                                        $val_temp = $key;
                                        break;
                                    }
                                }
                            }
                            $valarr[] = $val_temp;
                        }
                    }
                }
                if(count($strarr) >0 && $param['q']) {
                     $model->WhereRaw(implode(" OR ",$strarr),$valarr);
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

        $items = $model->orderBy("scanner_activity.id","DESC");
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