<?php

namespace App\Http\Controllers\BannerBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\BannerModel\BannerForum;
class BannerForumController extends ScafoldController
{
   
    public function __construct(){

        $model = new BannerForum;
        $this->setModelController($model);

        $this->setTypeOfField("bf_id","hidden");
        // $this->setTypeOfField("bf_link","hidden");
        $this->setTypeOfField("bf_file","file");
        $this->setTypeOfField("bf_date","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['bf_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('FORUM_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('FORUM_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","banner-forum")->first());
    }

}