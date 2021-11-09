<?php

namespace App\Http\Controllers\BannerBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\BannerModel\BannerHome;
class BannerHomeController extends ScafoldController
{
   
    public function __construct(){

        $model = new BannerHome;
        $this->setModelController($model);

        $this->setTypeOfField("bh_id","hidden");
        // $this->setTypeOfField("bh_link","hidden");
        $this->setTypeOfField("bh_file","file");
        $this->setTypeOfField("bh_date","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['bh_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('MOBILE_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('MOBILE_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","banner-home")->first());
    }

}