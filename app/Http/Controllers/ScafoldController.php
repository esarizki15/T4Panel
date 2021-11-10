<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ScafoldController extends MYBaseController
{   

    var $modelScafold ;
    var $showModelScafold ;
    var $itemCollection ;
    var $typeOfFields ;
    var $labelOfFields ;
    var $fieldRelation ;
    var $storagePath = "" ;
    var $urlPath = "" ;
    var $thumbWidth =278;
    var $thumbHeight=139 ;
    var $exportClassFunction ;
    var $dateRangeField ;
 
    public function setModelController($model=null){
          $this->modelScafold = new $model;
    }


    public function getModelController(){
        return $this->modelScafold;
    }

    public function setShowModelScafold($field=null){
          $this->showModelScafold = $field ;
    }

    public function getShowModelScafold(){
         return  $this->showModelScafold ;
    }

    public function setCollections($collection=null){
          $this->itemCollection = $collection;
    }

    public function setFieldRelation($field=null,$type=["name"=>"","type"=>"","data"=>[],"push"=>false]){
          if($field) $this->fieldRelation[$field] = $type;
    }

    public function setTypeOfField($field=null,$values="text",$label=null){
          if($field) $this->typeOfFields[$field] = $values;
          if($field&&$label) $this->labelOfFields[$field] = $label;
    }

    public function setExportClassFunction($exportClass=null) {
          $this->exportClassFunction = new $exportClass;
    }

    public function setDateRangeField($field=null){
          $this->dateRangeField = $field ;
    }
    public function setStoragePath($path=null){
          $this->storagePath = $path ;
    }
    public function getStoragePath($path=null){
         return $this->storagePath ;
    }
    public function setUrlPath($path=null){
          $this->urlPath = $path ;
    }
    public function getUrlPath($path=null){
         return $this->urlPath ;
    }
    public function setThumbWidth($w=278){
            $this->thumbWidth = $w ;
    }
    public function setThumbHeight($h=139){
            $this->thumbHeight = $h ;
    }
    public function getThumbWidth(){
            return $this->thumbWidth ;
    }
    public function getThumbHeight(){
            return $this->thumbHeight ;
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->setTypeOfField("supplier_id","hidden");
        // start mysql
        if(\Request::route()->getName() == "menu.create") {
            $arr = ["supplier_id", "parent_id", "slug", "url", "platform_id", "sort", "position_id", "is_absolute_url", "state", "active", "created_at", "updated_at", "permission_id"];
            foreach($arr as $data) {
                $this->setTypeOfField($data, "hidden");
            }
        }
        // end mysql
        $caption = $this->getCaption();
        $connectionname = $this->getModelController()->getConnectionName();
        $columns = \DB::connection($connectionname)->select( \DB::connection($connectionname)->raw(' 
        select column_name as name, data_type as type , is_nullable as not_required , 
         CASE WHEN column_name =\'slug\' THEN \'disabled\'
            ELSE \'\'
        END as disabled 

        from INFORMATION_SCHEMA.COLUMNS where table_name =\''.$this->getModelController()->getTable().'\' '));
        
        $columns = collect($columns)->unique('name'); //for mysql
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

        return $this->view('scafold-bundle.scafolding.create',compact('columns', "caption" ));
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
         if(in_array('slug', $columnArr)) if(isset($data['name'])) $data['slug'] = slugify($data['name']);
        $data['supplier_id'] = 1;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $caption = $this->getCaption();
        $items = $this->getModelController()::where($this->getShowModelScafold(),$id)->paginate(50);
        return $this->view('scafold-bundle.scafolding.index',compact('items', "caption" ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        if(in_array('slug', $columnArr)) if(isset($data['name'])) $data['slug'] = slugify($data['name']);
        $data['supplier_id'] = 1;
        $msg = " doesnt happened ";
        try{
            $msg = 'updated successfully';
            foreach($data as &$val) {
                if(is_array($val)) $val = json_encode($val);
            }
            $stat = $model->update($data);
        }catch(\Illuminate\Database\QueryException  $e){
                    $msg = ' updated failed '. $e->getMessage();
        }
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //change to soft delete
        $model = $this->getModelController();
        $primaryKey = $model->getKeyName();
        $group = $model::where($primaryKey, $id)->firstOrFail();
        $group->delete();
        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => ' deleted successfully']);
    }

    
}
