<?php

namespace App\Http\Controllers\ResidentServiceBundle;

use Illuminate\Http\Request;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\EventQrPicModel;
use  Illuminate\Support\Facades\Route;
use App\Http\Model\MemberModel\Member;
use App\Http\Model\AppointmentModel\AppointmentServiceLocationModel;
class EventQrPicController extends ScafoldController
{
    public function __construct(){
        $model = new EventQrPicModel;
        $this->setModelController($model);
        $this->setTypeOfField("member_id","text","phone_number");

        $collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );

        $collection['event_at']= json_decode( json_encode([ 
            // ["id"=>"CUSTOMER_SERVICE", "name"=> "CUSTOMER SERVICE" ] , 
            // ["id"=>"GATE_SECURE", "name"=> "SECURITY GATE" ] , 
            // ["id"=>"RESIDENTIAL", "name"=> "RESIDENTIAL" ] , 
            // ["id"=>"CLUB_HOUSE", "name"=> "CLUB HOUSE" ] , 
            // ["id"=>"VACCINE_REGISTRATION", "name"=> "VACCINE REGISTRATION" ] , 
            ["id"=>"TREATMENT_SERVICE", "name"=> "TREATMENT" ] , 
        ]) );
        $appointmentLocation =  AppointmentServiceLocationModel::select("id","location_name")->where("client_type","TREATMENT_SERVICE")->get();
        $appointmentLocationRemap = [];
        $appointmentLocationRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($appointmentLocation as $val){
            $appointmentLocationRemap[]=["id"=>$val->id, "name" =>  $val->location_name  ];
        }
        $collection['location'] = json_decode(json_encode($appointmentLocationRemap));

        $this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","residentservice-event-qr-pic")->first());
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
                \DB::raw("event_qr_pic.id as id"),
                \DB::raw("event_qr_pic.event_at"),
                \DB::raw("member.member_username as phone_number"),
                \DB::raw("appointment_service_location.id as location"),
                \DB::raw(" event_qr_pic.status 
                          "),
            ]);
        $model->leftJoin('member','member.member_id','=','event_qr_pic.member_id') ;
        $model->leftJoin('appointment_service_location','appointment_service_location.id','=','event_qr_pic.location') ;
        $model->whereIn('event_qr_pic.event_at',['TREATMENT_SERVICE']);
        $assetsPath = $this->getUrlPath();

        $caption = $this->getCaption();
        $columns = json_decode(json_encode([ 
            [ "name" => "id" , "type" => "character varying" , "table" => "" , "filter" => false ],
            [ "name" => "event_at" , "type" => "character varying" , "table" => "" , "filter" => false ],
            [ "name" => "phone_number" , "type" => "character varying" , "table" => "" , "where" => "member.member_username" ],
            [ "name" => "location" , "type" => "character varying" , "table" => "" , "where" => "appointment_service_location.location_name" ],
            [ "name" => "status", "type" => "int", "table" => "", "where" => "event_qr_pic.status", "dict" => [
                "0" => "UNPUBLISHED", 
                "1" => "PUBLISHED", 
                ]
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

        $items = $model->orderBy("event_qr_pic.id","DESC");
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

    public function store(Request $request)
    {
        //todo :: image 1140 x 460
        $data = $request->all();
        $connectionname = $this->getModelController()->getConnectionName();
        $columns = \DB::connection($connectionname)->select( \DB::connection($connectionname)->raw(' 
        select column_name as name 
        from INFORMATION_SCHEMA.COLUMNS where table_name =\''.$this->getModelController()->getTable().'\' '));
        $columnArr = [];
        foreach($columns as $val){
            $columnArr[] = $val->name;
        }
        $msg = ' doesnt happened ';
        try{
            $member_id = 0;
            if($data['member_id']){
                $memberUsername = $data['member_id'];
                $nomor = str_replace(['+',' ','-'],['','',''],trim($memberUsername));
                if(substr($nomor,0,1)=='0') $memberUsername = '62'.substr($memberUsername,1);
            }
            $member =  Member::where("member_username",$memberUsername)->first();
            if($member){
                $member_id = $member->member_id;
            }
            if($member_id) {
                $data['member_id'] = $member_id;
                $this->getModelController()::create($data);
                $msg = ' added successfully';
            }
        }catch(\Illuminate\Database\QueryException  $e){
                    $msg = ' added failed '. $e->getMessage();
        }

        return redirect()->route( current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
   }

    public function edit($id)
    {
        //
        $this->setTypeOfField("supplier_id","hidden");
        $caption = $this->getCaption();
        $assetsPath = $this->getUrlPath();
        $model = $this->getModelController()::findOrFail($id);
        $primaryKey = $model->getKeyName();
        if(isset($model) ){
            if($primaryKey != 'id' ) {
                    $model->id = $model->$primaryKey;
            }
            $member =  Member::where("member_id",$model->member_id)->first();
            if($member) 
            {
                $model->member_id = $member->member_username;
            }
        }
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
        $columnArr = [] ;
        foreach($columns as $val){
            $columnArr[$val->name] = $val;
        }
        $columns = $columnArr;
        return  $this->view('scafold-bundle.scafolding.edit',compact('model', "caption" ,'columns','assetsPath' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $model = $this->getModelController();
        $primaryKey = $model->getKeyName();
        $model = $model::where($primaryKey, $id)->firstOrFail();
        $data = $request->all();
        $connectionname = $this->getModelController()->getConnectionName();
        $columns = \DB::connection($connectionname)->select( \DB::connection($connectionname)->raw(' 
        select column_name as name 
        from INFORMATION_SCHEMA.COLUMNS where table_name =\''.$this->getModelController()->getTable().'\' '));
        $columnArr = [];
        foreach($columns as $val){
            $columnArr[] = $val->name;
        }
        $msg = " doesnt happened ";
         try{
            $member_id = 0;
            if($data['member_id']){
                $memberUsername = $data['member_id'];
                $nomor = str_replace(['+',' ','-'],['','',''],trim($memberUsername));
                if(substr($nomor,0,1)=='0') $memberUsername = '62'.substr($memberUsername,1);
            }
            $member =  Member::where("member_username",$memberUsername)->first();
            if($member){
                $member_id = $member->member_id;
            }
            if($member_id) {
                $data['member_id'] = $member_id;
                $msg = 'updated successfully';
                $stat = $model->update($data);
           }

         }catch(\Illuminate\Database\QueryException  $e){
                    $msg = ' updated failed '. $e->getMessage();
        }
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
    }

}
