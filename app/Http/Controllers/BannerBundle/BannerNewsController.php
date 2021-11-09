<?php

namespace App\Http\Controllers\BannerBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\BannerModel\BannerNews;
class BannerNewsController extends ScafoldController
{
   
    public function __construct(){

        $model = new BannerNews;
        $this->setModelController($model);

        $this->setTypeOfField("bn_id","hidden");
        // $this->setTypeOfField("bn_link","hidden");
        $this->setTypeOfField("bn_file","file");
        $this->setTypeOfField("bn_date","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['bn_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('NEWS_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('NEWS_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","banner-news")->first());
    }

}