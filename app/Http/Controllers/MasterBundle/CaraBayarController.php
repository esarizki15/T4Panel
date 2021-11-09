<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\CaraBayar;
class CaraBayarController extends ScafoldController
{
   
    public function __construct(){

        $model = new CaraBayar;
        $this->setModelController($model);

        $this->setTypeOfField("cb_id","hidden");
        $this->setTypeOfField("cb_logo","file");
        $this->setTypeOfField("cb_ket","textarea-wysihtml5");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $collection['cb_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
        $this->setCollections($collection);

        $this->setStoragePath(  env('CARABAYAR_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('CARABAYAR_ASSETS_PATH','') );
        $this->setThumbWidth(260);
        $this->setThumbHeight(194); 
        $this->setCaption(\App\Http\Model\SubMenu::where("code","cara-bayar")->first());
    }

}