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
class ResidentServiceMemberController extends ScafoldController
{
    public function __construct(){
        $model = new AppointmentMemberModel;
        $this->setModelController($model);
        $this->setDateRangeField("picked_date");

        $this->setTypeOfField("document","file");

        $this->setStoragePath(  env('APPOINTMENT_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('APPOINTMENT_ASSETS_PATH','') );
        $this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","residentservice-member")->first());
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
                // \DB::raw("appointment_member.id as no"),
                // \DB::raw("appointment_member.appointment_code as code"),
                // \DB::raw("appointment_service_location.location_name as location"),
                \DB::raw("initcap(member.member_nm) as patient"),
                \DB::raw("appointment_member.picked_date as booking_date"),
                \DB::raw("appointment_member.picked_time as booking_time"),
                \DB::raw("appointment_services.service_name as treatment"),
                \DB::raw("appointment_services.price as price"),
                \DB::raw("CASE appointment_member.status 
                            WHEN 0 THEN 'DRAFT'
                            WHEN 1 THEN 'ACTIVE'
                            WHEN 2 THEN 'FINISHED'
                            WHEN 3 THEN 'RESCHEDULED'
                            WHEN 4 THEN 'CANCELLED'
                            WHEN 6 THEN 'SCAN BY STAFF'
                            WHEN 7 THEN 'SCAN BY STAFF'
                            WHEN 8 THEN 'SCAN BY STAFF'
                            WHEN 9 THEN 'SCAN BY STAFF'
                        END AS status_name"),
                        
                \DB::raw("appointment_member.handling_by_panel as staff"),
            ]);
        $model->leftJoin('appointment_services','appointment_services.id','=','appointment_member.service_id') ;
        $model->leftJoin('appointment_sub_services','appointment_sub_services.id','=','appointment_member.sub_service_id') ;
        // $model->leftJoin('appointment_service_location','appointment_service_location.id','=','appointment_member.location_id') ;
        $model->leftJoin('member','member.member_id','=','appointment_member.member_id') ;
        $model->where('appointment_member.client_type','=','TREATMENT_SERVICE');
        $assetsPath = $this->getUrlPath();
        $caption = $this->getCaption();
        $columns = json_decode(json_encode([ 
            [ "name" => "no" , "type" => "character varying" , "table" => "" , "filter" => false ],
            [ "name" => "document" , "type" => "character varying" , "table" => "" , "filter" => false ],
            // [ "name" => "location" , "type" => "character varying" , "table" => "" , "where" => "appointment_service_location.location_name" ],
            [ "name" => "patient" , "type" => "character varying" , "table" => "" , "where" => "member.member_nm" ],
            // [ "name" => "booking_date" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.picked_date" ],
            [ "name" => "booking_time" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.picked_time" ],
            // [ "name" => "code" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.appointment_code" ],
            [ "name" => "treatment" , "type" => "character varying" , "table" => "" , "where" => "appointment_services.service_name" ],
            [ "name" => "price" , "type" => "character varying" , "table" => "" , "where" => "appointment_services.price" ],
            [ "name" => "staff" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.handling_by_panel" ],
            [ "name" => "status", "type" => "int", "table" => "", "where" => "appointment_member.status", "dict" => [
                "0" => "DRAFT", 
                "1" => "ACTIVE", 
                "2" => "FINISHED", 
                "3" => "RESCHEDULED", 
                "4" => "CANCELLED", 
                "6" => "SCAN BY STAFF", 
                "7" => "SCAN BY STAFF", 
                "8" => "SCAN BY STAFF",
                "9" => "SCAN BY STAFF"]
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

        $summaryActive =  AppointmentMemberModel::where('status','1')->where('picked_date','>=',date("Y-m-d"))->count();
        $summaryGateIn =  AppointmentMemberModel::where('status','6')->where('picked_date','=',date("Y-m-d"))->count();
        $summaryFinished =  AppointmentMemberModel::where('status','2')->where('picked_date','=',date("Y-m-d"))->count();
        $summaryTomorrow =  AppointmentMemberModel::where('status','1')->where('picked_date','=',date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')) )))->count();

        $summary['active'] = number_format($summaryActive);
        $summary['gate_in'] = number_format($summaryGateIn);
        $summary['finished'] = number_format($summaryFinished);
        $summary['tomorrow'] = number_format($summaryTomorrow);


        return $this->view('appointment-bundle.appointment-member.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType", "dateRangeField", "summary" ) );
    }

    public function show($id){
        $this->setTypeOfField("supplier_id","hidden");
        $caption = $this->getCaption();
        $assetsPath = $this->getUrlPath();
        $model = $this->getModelController()::findOrFail($id);
        $connectionname = $this->getModelController()->getConnectionName();
        $model =   \Illuminate\Support\Facades\DB::connection($connectionname)->select("
        select
            am.id,
            am.appointment_schedule_id,
            am.appointment_code,
            am.appointment_date,
            am.gate_in,
            am.gate_out,
            -- am.received_by,
            am.handling_start,
            am.handling_end,
            am.status,
            CASE am.status 
                WHEN 0 THEN 'DRAFT'
                WHEN 1 THEN 'ACTIVE'
                WHEN 2 THEN 'FINISHED'
                WHEN 3 THEN 'RESCHEDULED'
                WHEN 4 THEN 'CANCELLED'
                WHEN 6 THEN 'GATE IN'
                WHEN 7 THEN 'GATE OUT'
                WHEN 8 THEN 'HANDLE BY CS'
                WHEN 9 THEN 'DONE BY CS'
            END AS status_name,
            am.picked_date,
            am.picked_time,
            am.member_address,
            am.identity_code,
            am.appointment_description,
            am.handling_by_panel,
            am.notes,
            am.reschedule_date_to,
            am.reschedule_time_to,
            
            m1.member_nm as received_by,
            m2.member_nm as gate_in_received_by,
            m3.member_nm as member_name,
            m4.member_nm as gate_out_received_by,
            
            am.handling_start,
            am.handling_end,
            
            as2.service_name,
            asl.location_name,
            ass.sub_service_name
            
        from appointment_member am 
        left join \"member\" m1 on m1.member_id = am.received_by::int
        left join \"member\" m2 on m2.member_id = am.gate_in_received_by::int
        left join \"member\" m3 on m3.member_id = am.member_id::int
        left join \"member\" m4 on m4.member_id = am.gate_out_received_by::int
        left join appointment_services as2 on as2.id = am.service_id::int
        left join appointment_service_location asl on asl.id = am.location_id::int
        left join appointment_sub_services ass on ass.id = am.sub_service_id::int
        where am.id = {$id}
        ");
        if (count($model) < 1){
            return redirect()->route(current(explode('.', Route::currentRouteName())) . '.index',$id)->with(['message' => "invalid id" ]);
        }
        $model = $model[0];
        return  $this->view('appointment-bundle.appointment-member.show',compact('model', "caption" ,'assetsPath' ));
    }
   
    public function updateStart(Request $request, $id){
        $user = \Auth::user();
        $model = $this->getModelController();
        $primaryKey = $model->getKeyName();
        $model = $model::where($primaryKey, $id)->first();
        $msg = 'nothing happen';
        if($model){
            if(in_array($model['status'], [1,6])){
                  try{
                    $data['status'] = 8;
                    $data['handling_start'] = date("Y-m-d H:i:s");
                    $data['handling_by_panel'] = $user->name;
                    $msg = 'started handling customer';
                    $stat = $model->update($data);

                 }catch(\Illuminate\Database\QueryException  $e){
                            $msg = 'failed '. $e->getMessage();
                }
             }else{
                 $msg = 'this booking already taken';
             }
        }
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.show',$id)->with(['message' => $msg ]);
    }
    public function updateStop(Request $request, $id){
        $user = \Auth::user();
        $model = $this->getModelController();
        $primaryKey = $model->getKeyName();
        $model = $model::where($primaryKey, $id)->first();
        $msg = 'nothing happen';
        $param = $request->all();
        if($model){
            if(in_array($model['status'], [2,8])){
                  try{
                    $data['notes'] = isset($param['notes']) ? $param['notes']: "";
                    $data['status'] = 2;
                    $data['handling_end'] = date("Y-m-d H:i:s");
                    $msg = 'finish handling customer';
                    $stat = $model->update($data);

                 }catch(\Illuminate\Database\QueryException  $e){
                            $msg = 'failed '. $e->getMessage();
                }
             }else{
                 $msg = 'this booking already taken';
             }
        }
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.show',$id)->with(['message' =>$msg ]);
    }
    public function updateCancel(Request $request, $id){
        $user = \Auth::user();
        $model = $this->getModelController();
        $primaryKey = $model->getKeyName();
        $model = $model::where($primaryKey, $id)->first();
        $msg = 'nothing happen';
        $param = $request->all();
        if($model){
            if(in_array($model['status'], [1])){
                  try{
                    $data['notes'] = isset($param['notes']) ? $param['notes']: "";
                    $data['status'] = 4;
                    $data['handling_by_panel'] = $user->name;
                    // $data['handling_end'] = date("Y-m-d H:i:s");
                    $msg = 'canceled booking';
                    $stat = $model->update($data);

                 }catch(\Illuminate\Database\QueryException  $e){
                            $msg = 'failed '. $e->getMessage();
                }
             }else{
                 $msg = 'this booking already taken';
             }
        }
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.show',$id)->with(['message' => $msg ]);
    }
    public function updateNotes(Request $request, $id){
        $user = \Auth::user();
        $model = $this->getModelController();
        $primaryKey = $model->getKeyName();
        $model = $model::where($primaryKey, $id)->first();
        $msg = 'nothing happen';
        $param = $request->all();
        if ($model){
            if (in_array($model['status'], [2])){
                try {
                    $data['notes'] = isset($param['notes']) ? $param['notes'] : "";
                    $msg = 'updated booking notes';
                    $stat = $model->update($data);
                } catch (\Illuminate\Database\QueryException  $e) {
                    $msg = 'failed ' . $e->getMessage();
                }
            } else {
                $msg = 'this booking cannot update';
            }
        }
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.show',$id)->with(['message' => $msg ]);
    }
    public function summaryData(Request $request){
        $param = $request->all();
        $latest = isset($param['latest_gate_in']) ? $param['latest_gate_in'] : 0;
        $summaryActive =  AppointmentMemberModel::where('status','1')->where('picked_date','>=',date("Y-m-d"))->count();
        $summaryGateIn =  AppointmentMemberModel::where('status','6')->where('picked_date','=',date("Y-m-d"))->count();
        $summaryFinished =  AppointmentMemberModel::where('status','2')->where('picked_date','=',date("Y-m-d"))->count();
        $summaryTomorrow =  AppointmentMemberModel::where('status','1')->where('picked_date','=',date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')) )))->count();
        $summaryGateInNew =  ( $latest >= $summaryGateIn ) ? false : true  ;

        $summary['active'] = number_format($summaryActive)." <span style='font-size: 10px'>customer</span>";
        $summary['gate_in'] = number_format($summaryGateIn)." <span style='font-size: 10px'>customer</span>";
        $summary['finished'] = number_format($summaryFinished)." <span style='font-size: 10px'>customer</span>";
        $summary['tomorrow'] = number_format($summaryTomorrow)." <span style='font-size: 10px'>customer</span>";

        $summary['has_new_gate_in'] = isset($param['latest_gate_in']) ? $summaryGateInNew : false;

        return response()->json($summary);

    }
}
