<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\CategoryProduct;
class CategoryProductController extends ScafoldController
{
   
    public function __construct(){

        $model = new CategoryProduct;
        $this->setModelController($model);

        $this->setTypeOfField("kp_id","hidden");
        $this->setTypeOfField("kp_jum","hidden");
        $this->setTypeOfField("kp_gbr","file");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
		$collection['kp_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('CATEGORY_PRODUCT_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('CATEGORY_PRODUCT_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","category-product")->first());
    }

}