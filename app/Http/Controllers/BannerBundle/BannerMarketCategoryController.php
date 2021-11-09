<?php

namespace App\Http\Controllers\BannerBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\BannerModel\BannerMarketCategory;
class BannerMarketCategoryController extends ScafoldController
{
   
    public function __construct(){

        $model = new BannerMarketCategory;
        $this->setModelController($model);

        $this->setTypeOfField("bmc_id","hidden");
        // $this->setTypeOfField("bmc_link","hidden");
        $this->setTypeOfField("bmc_file","file");
        $this->setTypeOfField("bmc_date","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['bmc_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('CATEGORY_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('CATEGORY_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","banner-market-category")->first());
    }

}