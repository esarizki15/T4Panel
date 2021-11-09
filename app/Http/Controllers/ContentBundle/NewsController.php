<?php

namespace App\Http\Controllers\ContentBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ContentModel\News;
use App\Http\Model\ContentModel\NewsCategory;
class NewsController extends ScafoldController
{
   
    public function __construct(){

        $model = new News;
        $this->setModelController($model);
        $this->setTypeOfField("news_judul","text","judul");
        $this->setTypeOfField("news_id","hidden");
        // $this->setTypeOfField("news_link","hidden");
        $this->setTypeOfField("news_last_update","hidden");
        $this->setTypeOfField("news_date","text-datepicker");
        $this->setDateRangeField("news_date");
        $this->setTypeOfField("news_status",null,"status");
        $this->setTypeOfField("news_category",null,"kategori");

        $this->setTypeOfField("news_gbr","file","gambar");
        $this->setTypeOfField("news_gbr2","file","gambar");
        $this->setTypeOfField("news_gbr3","file","gambar");
        $this->setTypeOfField("news_isi","textarea-wysihtml5","konten");
        $this->setTypeOfField("news_tag","text","tag");
        $this->setTypeOfField("news_link","text-long","link");
        $this->setTypeOfField("news_author","text","author");
        $this->setTypeOfField("news_editor","text","editor");
        // $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

    	$collection['news_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ] , ["id"=>"2", "name"=> "HIGHLIGHT" ] ]) );
        
        $newsCategory =  NewsCategory::select("id","name")->WhereRaw('status = 1')->get();
        $newsCategoryRemap = [];
        $newsCategoryRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($newsCategory as $val){
            $newsCategoryRemap[]=["id"=>$val->id, "name" =>  $val->name  ];
        }
        $collection['news_category'] = json_decode(json_encode($newsCategoryRemap));

		$this->setCollections($collection);
        $this->setStoragePath(  env('NEWS_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('NEWS_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","news")->first());
    }

}