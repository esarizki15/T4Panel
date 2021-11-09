<?php

namespace App\Http\Controllers\ResidentServiceBundle;

use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\AppointmentMemberModel;
use App\Http\Model\MemberModel\Member;
use App\Http\Model\AppointmentModel\AppointmentScheduleModel;
use App\Http\Model\AppointmentModel\AppointmentServicesModel;
use App\Http\Model\AppointmentModel\AppointmentSubServicesModel;
use App\Http\Model\AppointmentModel\AppointmentServiceLocationModel;
class ResidentServiceReportController extends ScafoldController
{
    public function __construct(){
        $model = new AppointmentMemberModel;
        $this->setModelController($model);
        $this->setDateRangeField("picked_date");
        $this->setCaption(\App\Http\Model\SubMenu::where("code","residentservice-report")->first());
    }

    public function index(Request $request)
    {   
        $this->setTypeOfField("supplier_id","hidden");
        $param = $request->all();
        $user = \Auth::user();
        $grantType = $user->grantOption($user->role);
        $model = $this->getModelController(); 
        $primaryKey = $model->getKeyName();
        $assetsPath = $this->getUrlPath();
        $dateRangeField = $this->dateRangeField ;
        $caption = $this->getCaption();
        $model = $model::select([
                \DB::raw("appointment_member.id as id"),
                \DB::raw("appointment_service_location.location_name as location"),
                \DB::raw("initcap(member.member_nm) as customer"),
                \DB::raw("member.member_phone as mobile_number"),
                \DB::raw("member.member_email"),
                \DB::raw("member.member_addr as unit_address"),
                \DB::raw("appointment_member.picked_date as booking_date"),
                \DB::raw("appointment_member.picked_time as booking_time"),
                \DB::raw("appointment_member.reschedule_date_to as reschedule_date"),
                \DB::raw("appointment_member.reschedule_time_to as reschedule_time"),
                \DB::raw("appointment_services.service_name as service"),
                \DB::raw("appointment_sub_services.sub_service_name as sub_service"),
                \DB::raw("appointment_member.appointment_description as description"),
                \DB::raw("appointment_member.notes"),
                \DB::raw("CASE appointment_member.status 
                            WHEN 0 THEN 'DRAFT'
                            WHEN 1 THEN 'ACTIVE'
                            WHEN 2 THEN 'FINISHED'
                            WHEN 3 THEN 'RESCHEDULED'
                            WHEN 4 THEN 'CANCELLED'
                            WHEN 6 THEN 'GATE IN'
                            WHEN 7 THEN 'GATE OUT'
                            WHEN 8 THEN 'HANDLE BY CS'
                            WHEN 9 THEN 'DONE BY CS'
                        END AS status_name"),
                \DB::raw("appointment_member.identity_code as plat_number"),
                \DB::raw("appointment_member.handling_by_panel as cs_officer"),
                \DB::raw("to_char(appointment_member.gate_in, 'HH24:MI:SS') as gate_in_time"),
                \DB::raw("to_char(appointment_member.handling_start, 'HH24:MI:SS') as handling_start"),
                \DB::raw("to_char(appointment_member.handling_end, 'HH24:MI:SS') as handling_end"),
                \DB::raw("concat(
                    coalesce(DATE_PART('hour',appointment_member.handling_start::timestamp - appointment_member.gate_in::timestamp), 0), ' hours, ',
                    coalesce(DATE_PART('minute',appointment_member.handling_start::timestamp - appointment_member.gate_in::timestamp), 0), ' minutes, ',
                    coalesce(DATE_PART('second',appointment_member.handling_start::timestamp - appointment_member.gate_in::timestamp), 0),  ' seconds'
                ) waiting_time"),
                \DB::raw("concat(
                    coalesce(DATE_PART('hour',appointment_member.handling_end::timestamp - appointment_member.handling_start::timestamp), 0), ' hours, ',
                    coalesce(DATE_PART('minute',appointment_member.handling_end::timestamp - appointment_member.handling_start::timestamp), 0), ' minutes, ',
                    coalesce(DATE_PART('second',appointment_member.handling_end::timestamp - appointment_member.handling_start::timestamp), 0),  ' seconds'
                ) as handling_time")
            ]);
        $model->leftJoin('appointment_services','appointment_services.id','=','appointment_member.service_id') ;
        $model->leftJoin('appointment_sub_services','appointment_sub_services.id','=','appointment_member.sub_service_id') ;
        $model->leftJoin('appointment_service_location','appointment_service_location.id','=','appointment_member.location_id') ;
        $model->leftJoin('member','member.member_id','=','appointment_member.member_id') ;
        $model->where('appointment_member.client_type','=','RESIDENT_SERVICE');
        $assetsPath = $this->getUrlPath();
        $caption = $this->getCaption();
        $columns = json_decode(json_encode([ 
            [ "name" => "no" , "type" => "character varying" , "table" => "" , "filter" => false ],
            [ "name" => "location" , "type" => "character varying" , "table" => "" , "where" => "appointment_service_location.location_name" ],
            [ "name" => "customer" , "type" => "character varying" , "table" => "" , "where" => "member.member_nm" ],
            // [ "name" => "booking_date" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.picked_date" ],
            [ "name" => "booking_time" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.picked_time" ],
            [ "name" => "reschedule_date" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.reschedule_date_to" ],
            [ "name" => "reschedule_time" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.reschedule_time_to" ],
            // [ "name" => "code" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.appointment_code" ],
            [ "name" => "service" , "type" => "character varying" , "table" => "" , "where" => "appointment_services.service_name" ],
            [ "name" => "sub_service" , "type" => "character varying" , "table" => "" , "where" => "appointment_sub_services.sub_service_name" ],
            [ "name" => "description" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.appointment_description" ],
            [ "name" => "notes", "type" => "character varying", "table" => "", "where" => "appointment_member.notes"],
            // [ "name" => "number" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.identity_code" ],
            [ "name" => "cs_officer" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.handling_by_panel" ],
            [ "name" => "status", "type" => "int", "table" => "", "where" => "appointment_member.status", "dict" => [
                "0" => "DRAFT", 
                "1" => "ACTIVE", 
                "2" => "FINISHED", 
                "3" => "RESCHEDULED", 
                "4" => "CANCELED", 
                "6" => "GATE IN", 
                "7" => "GATE OUT", 
                "8" => "HANDLE BY CS",
                "9" => "DONE BY CS"]
            ]
 
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

        $items = $model->orderBy("appointment_member.id","DESC");
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

        return $this->view('appointment-bundle.appointment-report.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType", "dateRangeField"  ) );
    } 
}
