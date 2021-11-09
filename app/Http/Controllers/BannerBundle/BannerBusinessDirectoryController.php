<?php

namespace App\Http\Controllers\BannerBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\BannerModel\BannerBusinessDirectory;
class BannerBusinessDirectoryController extends ScafoldController
{
   
    public function __construct(){

        $model = new BannerBusinessDirectory;
        $this->setModelController($model);
        
        $this->setTypeOfField("bbd_id","hidden");
        // $this->setTypeOfField("bbd_link","hidden");
        $this->setTypeOfField("bbd_file","file");
        $this->setTypeOfField("bbd_date","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['bbd_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('BUSINESS_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('BUSINESS_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);


        $this->setCaption(\App\Http\Model\SubMenu::where("code","banner-business-directory")->first());
    }

}