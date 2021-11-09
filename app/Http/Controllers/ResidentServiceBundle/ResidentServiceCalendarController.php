<?php

namespace App\Http\Controllers\ResidentServiceBundle;

use Illuminate\Http\Request;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\AppointmentMemberModel;
use App\Http\Model\MemberModel\Member;
use  Illuminate\Support\Facades\Route;
use App\Http\Model\AppointmentModel\AppointmentScheduleModel;
use App\Http\Model\AppointmentModel\AppointmentServicesModel;
use App\Http\Model\AppointmentModel\AppointmentServiceLocationModel;

class ResidentServiceCalendarController extends ScafoldController
{
    public function __construct(){
        $model = new AppointmentMemberModel;
       $this->setModelController($model);
       $this->setCaption(\App\Http\Model\SubMenu::where("code","residentservice-calendar")->first());
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
               \DB::raw("appointment_member.appointment_code as code"),
               \DB::raw("appointment_services.service_name as service"),
               \DB::raw("appointment_sub_services.sub_service_name as sub_service"),
               \DB::raw("appointment_service_location.location_name as location"),
               \DB::raw("member.member_nm as fullname"),
               \DB::raw("appointment_member.picked_date as date"),
               \DB::raw("appointment_member.picked_time as time"),
               \DB::raw("appointment_member.reschedule_date_to as reschedule_date"),
               \DB::raw("appointment_member.reschedule_time_to as reschedule_time"),
               \DB::raw("appointment_member.identity_code as number"),
               \DB::raw("appointment_member.appointment_description as description"),
               \DB::raw("appointment_member.handling_by_panel as cs_officer"),
           ]);
       $model->leftJoin('appointment_services','appointment_services.id','=','appointment_member.service_id') ;
       $model->leftJoin('appointment_sub_services','appointment_sub_services.id','=','appointment_member.sub_service_id') ;
       $model->leftJoin('appointment_service_location','appointment_service_location.id','=','appointment_member.location_id') ;
       $model->leftJoin('member','member.member_id','=','appointment_member.member_id') ;
       $model->whereIn("appointment_member.status",[1,6,7,8]);
        $model->where('appointment_member.picked_date','>=',date("Y-m-d"));
       $model->where('appointment_member.client_type','=','RESIDENT_SERVICE');
       $assetsPath = $this->getUrlPath();
       $caption = $this->getCaption();
       $columns = json_decode(json_encode([ 
           [ "name" => "id" , "type" => "character varying" , "table" => "" , "filter" => false ],
           [ "name" => "code" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.appointment_code" ],
           [ "name" => "service" , "type" => "character varying" , "table" => "" , "where" => "appointment_services.service_name" ],
           [ "name" => "sub_service" , "type" => "character varying" , "table" => "" , "where" => "appointment_sub_services.sub_service_name" ],
           [ "name" => "location" , "type" => "character varying" , "table" => "" , "where" => "appointment_service_location.location_name" ],
           [ "name" => "fullname" , "type" => "character varying" , "table" => "" , "where" => "member.member_nm" ],
           [ "name" => "date" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.picked_date" ],
           [ "name" => "time" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.picked_time" ],
           [ "name" => "reschedule_date" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.reschedule_date_to" ],
           [ "name" => "reschedule_time" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.reschedule_time_to" ],
           [ "name" => "number" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.identity_code" ],
           [ "name" => "description" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.appointment_description" ],
           [ "name" => "cs_officer" , "type" => "character varying" , "table" => "" , "where" => "appointment_member.handling_by_panel" ],

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
       $schedule = [];
       if($items) {
           foreach($items as $val){
               $schedule[]=[
                       "title" => "
                           {$val->service}  
                           {$val->fullname}
                             
                           {$val->location}  
                           
                       "  ,
                       "start" => $val->date." ".$val->time ,
                       // "end" => $val->picked_date ,
                       "url" => route("appointment-calendar.show",$val->id) ,
                       "backgroundColor" => "#00a65a" ,
                       "borderColor" => "#00a65a" ,
               ];
           }

       }
       return $this->view('appointment-bundle.appointment-calendar.index',compact( "caption", "schedule" ) );
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
}
