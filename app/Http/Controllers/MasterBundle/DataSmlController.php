<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\DataSml;
class DataSmlController extends ScafoldController
{
   
    public function __construct(){

        $model = new DataSml;
        $this->setModelController($model);
        $this->setTypeOfField('id',"hidden");
        $this->setTypeOfField('name',"hidden");
        $this->setTypeOfField('email',"hidden");
        $this->setTypeOfField('password',"hidden");
        $this->setTypeOfField('address',"text","alamat");
        $this->setTypeOfField('project_name',"text","nama_projek");
        $this->setTypeOfField('cluster_name',"text","nama_kluster");
        $this->setTypeOfField('project_cluster_code',"text","kode_projek");
        $this->setTypeOfField('res_number',"text","kode_ipl");
		$collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "OPEN" ] , ["id"=>"1", "name"=> "USED" ], ["id"=>"2", "name"=> "USED HAS SCHEDULE" ]   ]) );
		$this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","data-sml")->first());
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

        return $this->view('master-bundle.data-sml.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType" ) );
    }

    public function synch($res_number){

        $connectionname = $this->getModelController()->getConnectionName();
        $columns = \DB::connection($connectionname)->select( \DB::connection($connectionname)->raw("select generatebill('".$res_number."','".date("Ym")."')"));

         return redirect()->route(current(explode('.', Route::currentRouteName())) . '.index', ['fbfl' => 'res_number', 'q' => $res_number]);

    }

}