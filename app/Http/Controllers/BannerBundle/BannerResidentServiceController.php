<?php

namespace App\Http\Controllers\BannerBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\BannerModel\BannerResidentService;
class BannerResidentServiceController extends ScafoldController
{
   
    public function __construct(){

        $model = new BannerResidentService;
        $this->setModelController($model);

        $this->setTypeOfField("bmp_id","hidden");
        // $this->setTypeOfField("bmp_link","hidden");
        $this->setTypeOfField("bmp_file","file");
        $this->setTypeOfField("bmp_date","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['bmp_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('BANNER_MARKET_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('BANNER_MARKET_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","banner-resident-service")->first());
    }

}