<?php

namespace App\Http\Controllers\ProductBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ProductModel\Product;
use App\Http\Model\MemberModel\Member;
use App\Http\Model\MasterModel\CategoryProduct;
class ProductController extends ScafoldController
{
   
    public function __construct(){

        $model = new Product;
        $this->setModelController($model);

        $this->setTypeOfField("produk_id","label");
        $this->setTypeOfField("produk_nm","text","nama_produk");
        $this->setTypeOfField("produk_date","hidden");
        $this->setTypeOfField("produk_harga","number","harga");
        $this->setTypeOfField("produk_berat","text","berat");
        $this->setTypeOfField("produk_keterangan","text","keterangan");
        $this->setTypeOfField("produk_stok","text","stok");
        $this->setTypeOfField("produk_diskon","text","diskon");
        $this->setTypeOfField("produk_seo","text","seo");
        $this->setTypeOfField("produk_unit",null,"satuan_unit");
        $this->setTypeOfField("kp_id",null,"kategori");
        $this->setTypeOfField("produk_status",null,"status");
        $this->setTypeOfField("produk_show","hidden");
        $this->setTypeOfField("produk_keterangan","hidden-list");
        $this->setTypeOfField("produk_seo","hidden-list");
        $this->setTypeOfField("produk_panjang","hidden");
        $this->setTypeOfField("produk_lebar","hidden");
        $this->setTypeOfField("produk_tinggi","hidden");
        $this->setTypeOfField("produk_tags","hidden");

        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $collection['produk_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ], ["id"=>"2", "name"=> "DELETED"  ]  ]) );
		$collection['produk_unit']= json_decode( json_encode([ ["id"=>"gram", "name"=> "Gram" ] , ["id"=>"kg", "name"=> "Kilogram" ]  ]) );

	 	$member =  Member::select("member_id","member_nm")->WhereRaw('member_status <> 9')->get();
        $memberRemap = [];
        $memberRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemap[]=["id"=>$val->member_id, "name" =>  $val->member_nm  ];
        }
        $collection['member_id'] = json_decode(json_encode($memberRemap));

        $categoryProduct =  CategoryProduct::select("kp_id","kp_nm")->get();
        $categoryProductRemap = [];
        $categoryProductRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($categoryProduct as $val){
            $categoryProductRemap[]=["id"=>$val->kp_id, "name" =>  $val->kp_nm  ];
        }
        $collection['kp_id'] = json_decode(json_encode($categoryProductRemap));

		$this->setCollections($collection);


		$this->setFieldRelation("member_name",json_decode(json_encode(["name"=> "owner_name" , "type"=> "text", "data"=>[], "relations" => [ "member" , "member_nm" ] ])));
  		$this->setFieldRelation("member_id",json_decode(json_encode(["name"=> "member_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("member",json_decode(json_encode(["name"=> "member" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

        $this->setFieldRelation("category",json_decode(json_encode(["name"=> "category" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

        $this->setCaption(\App\Http\Model\SubMenu::where("code","product")->first());
    }

}