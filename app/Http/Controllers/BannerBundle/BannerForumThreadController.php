<?php

namespace App\Http\Controllers\BannerBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\BannerModel\BannerForumThread;
class BannerForumThreadController extends ScafoldController
{
   
    public function __construct(){

        $model = new BannerForumThread;
        $this->setModelController($model);

        $this->setTypeOfField("bft_id","hidden");
        // $this->setTypeOfField("bft_link","hidden");
        $this->setTypeOfField("bft_file","file");
        $this->setTypeOfField("bft_date","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['bft_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('FORUM_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('FORUM_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","banner-forum-thread")->first());
    }

}