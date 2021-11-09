<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\Category;
class CategoryController extends ScafoldController
{
   
    public function __construct(){

        $model = new Category;
        $this->setModelController($model);
        $this->setTypeOfField("id","hidden");
        $this->setTypeOfField("kategori_id","hidden");
        $this->setTypeOfField("kategori_parent","hidden");
        $this->setTypeOfField("kategori_level","hidden");

        $this->setTypeOfField("kategori_photo","file","icon");
        $this->setTypeOfField("kategori_image","file","image");
        $this->setTypeOfField("kategori_nm","text","name");
        $this->setTypeOfField("kategori_status",null,"status");
        $this->setTypeOfField("kategori_desc","text","description");
	    $this->setTypeOfField("kategori_jns",null,"type");

        $this->setStoragePath(  env('CATEGORY_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('CATEGORY_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);
		$collection['kategori_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED"  ] ] ) );
		$collection['kategori_jns']= json_decode( json_encode([ ["id"=>"0", "name"=> "PUBLIC" ] , ["id"=>"1", "name"=> "CLUSTER"  ] ,["id"=>"2", "name"=> "CLOSED" ] ] ) );
		$this->setCollections($collection);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","category")->first());
    }

}