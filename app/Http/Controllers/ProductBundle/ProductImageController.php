<?php

namespace App\Http\Controllers\ProductBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ProductModel\ProductImage;
use App\Http\Model\ProductModel\Product;
class ProductImageController extends ScafoldController
{
   
    public function __construct(){

        $model = new ProductImage;
        $this->setModelController($model);
 		$this->setTypeOfField("pg_id","hidden");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setTypeOfField("produk_id",null,"nama_produk");
		
        $this->setTypeOfField("pg_nm","file","gambar");
        $this->setStoragePath(  env('PRODUCT_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('PRODUCT_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);
     	$product =  Product::select("produk_id","produk_nm")->get();
        $productRemap = [];
        $productRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($product as $val){
            $productRemap[]=["id"=>$val->produk_id, "name" =>  $val->produk_nm  ];
        }
        $collection['produk_id'] = json_decode(json_encode($productRemap));
		$this->setCollections($collection);


        $this->setFieldRelation("product",json_decode(json_encode(["name"=> "product" , "type"=> "hidden", "data"=>[], "relations" => [] ])));



        $this->setCaption(\App\Http\Model\SubMenu::where("code","product-image")->first());
    }

}