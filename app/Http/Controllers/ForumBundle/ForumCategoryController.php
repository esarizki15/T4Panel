<?php

namespace App\Http\Controllers\ForumBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ForumModel\ForumCategory;
class ForumCategoryController extends ScafoldController
{
   
    public function __construct(){

        $model = new ForumCategory;
        $this->setModelController($model);
 		$this->setTypeOfField("fk_id","hidden");
 		$this->setTypeOfField("fk_nm","text","kategori");
 		$this->setTypeOfField("fk_status",null,"status");
 		
    	$collection['fk_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

        $this->setCaption(\App\Http\Model\SubMenu::where("code","forum-category")->first());
    }

}