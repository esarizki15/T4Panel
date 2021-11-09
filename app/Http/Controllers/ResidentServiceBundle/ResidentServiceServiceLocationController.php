<?php

namespace App\Http\Controllers\ResidentServiceBundle;

use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\AppointmentServiceLocationModel;
use App\Http\Model\AppointmentModel\AppointmentServicesModel;
use App\Http\Model\MasterModel\Category;
class ResidentServiceServiceLocationController extends  ScafoldController
{
    public function __construct(){
        $model = new AppointmentServiceLocationModel;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        $this->setTypeOfField("cluster_id","hidden");
        $this->setTypeOfField("service_id","multiple-select","services");
        $collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );

        $collection['client_type']= json_decode( json_encode([ ["id"=>"TREATMENT_SERVICE", "name"=> "TREATMENT" ] ]) );

        $category =  Category::select("kategori_id","kategori_nm")->where("kategori_jns",1)->get();
        $categoryRemap = [];
        $categoryRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($category as $val){
          $categoryRemap[]=["id"=>$val->kategori_id, "name" =>  $val->kategori_nm  ];
        }
        $collection['cluster_id'] = json_decode(json_encode($categoryRemap));

        $service =  AppointmentServicesModel::select("id","service_name")->where("status",1)->where('client_type', '=', 'TREATMENT_SERVICE')->get();
        $serviceRemap = [];
        $serviceRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($service as $val){
          $serviceRemap[]=["id"=>$val->id, "name" =>  $val->service_name  ];
        }
        $collection['service_id'] = json_decode(json_encode($serviceRemap));

        $this->setCollections($collection);  
        $this->setCaption(\App\Http\Model\SubMenu::where("code","residentservice-service-location")->first());
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
        $model->where('client_type', '=', 'TREATMENT_SERVICE');
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
        return $this->view('residentservice-bundle.residentservice-service-location.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType", "dateRangeField" ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

        return $this->view('residentservice-bundle.residentservice-service-location.create',compact('columns', "caption" ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //todo :: image 1140 x 460
        $data = $request->all();
         foreach($request->all() as $key => $val){
            if ($request->hasFile($key)) {
                $this->validate($request, [
                    $key => 'bail|mimes:jpeg,jpg,png', 
                ]);
                // check if from any
                $path = Storage::disk('public-api')->putFile($this->getStoragePath(), $request->file($key));
                if($path){
                        $data[$key] = $data[$key]->hashName();
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $manager = $manager->make(Storage::disk('public-api')->path($path));
                        if( $this->getThumbWidth() &&  $this->getThumbHeight() ) {
                            $manager = $manager->resize($this->getThumbWidth(), $this->getThumbHeight(),function ($constraint) {
                                                                                                                $constraint->aspectRatio();
                                                                                                            });
                            $manager = $manager->save(Storage::disk('public-api')->getDriver()->getAdapter()->getPathPrefix().$this->getStoragePath().'/thumb/'.$data[$key]);
                        }
                }
            }
         }
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
             foreach($data as &$val) {
                if(is_array($val)) $val = json_encode($val);
            }
            $this->getModelController()::create($data);
            $msg = ' added successfully';
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
        return  $this->view('residentservice-bundle.residentservice-service-location.edit',compact('model', "caption" ,'columns','assetsPath' ));
    }

    public function update(Request $request, $id)
    {
        //
        $model = $this->getModelController();
        $primaryKey = $model->getKeyName();
        $model = $model::where($primaryKey, $id)->firstOrFail();
         $data = $request->all();
         foreach($request->all() as $key => $val){
            if ($request->hasFile($key)) {
                $this->validate($request, [
                    $key => 'bail|mimes:jpeg,jpg,png', 
                ]);
                $path = Storage::disk('public-api')->putFile($this->getStoragePath(), $request->file($key));
                if($path){

                        $data[$key] = $data[$key]->hashName();
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $manager = $manager->make(Storage::disk('public-api')->path($path));
                        if( $this->getThumbWidth() &&  $this->getThumbHeight() ) {
                            $manager = $manager->resize($this->getThumbWidth(), $this->getThumbHeight(),function ($constraint) {
                                                                                                                $constraint->aspectRatio();
                                                                                                            });
                            $manager = $manager->save(Storage::disk('public-api')->getDriver()->getAdapter()->getPathPrefix().$this->getStoragePath().'/thumb/'.$data[$key]);
                        }
                }
            }
         }
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
            foreach($data as &$val) {
                if(is_array($val)) $val = json_encode($val);
            }
            $msg = 'updated successfully';
            $stat = $model->update($data);

         }catch(\Illuminate\Database\QueryException  $e){
                    $msg = ' updated failed '. $e->getMessage();
        }
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
    }
}
