<?php

namespace App\Http\Controllers\VoucherBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\VoucherModel\VoucherPartner;
use App\Http\Model\VoucherModel\Voucher;
use App\Http\Model\ContentModel\BusinessDirectory;
class VoucherPartnerController extends ScafoldController
{
   
    public function __construct(){

        $model = new VoucherPartner;
        $this->setModelController($model);

	  	$this->setFieldRelation("voucher_name",json_decode(json_encode(["name"=> "voucher_name" , "type"=> "text", "data"=>[], "relations" => [ "voucher" , "v_name" ] ])));
        $this->setFieldRelation("voucher_id",json_decode(json_encode(["name"=> "voucher_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("voucher",json_decode(json_encode(["name"=> "voucher" , "type"=> "hidden", "data"=>[], "relations" => [] ])));


	  	$this->setFieldRelation("directory_name",json_decode(json_encode(["name"=> "directory_name" , "type"=> "text", "data"=>[], "relations" => [ "business_directory" , "bd_nm" ] ])));
        $this->setFieldRelation("bd_id",json_decode(json_encode(["name"=> "bd_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("business_directory",json_decode(json_encode(["name"=> "business_directory" , "type"=> "hidden", "data"=>[], "relations" => [] ])));


        $this->setTypeOfField("image","file");

        $this->setTypeOfField("id","hidden");

       	$this->setStoragePath(  env('BUSINESS_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('BUSINESS_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);


        $voucher =  Voucher::select("id","v_name")->WhereRaw('status in (1,2) ')->get();
        $voucherRemap = [];
        $voucherRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($voucher as $val){
            $voucherRemap[]=["id"=>$val->id, "name" =>  $val->v_name  ];
        }
        $collection['voucher_id'] = json_decode(json_encode($voucherRemap));

       	$bd =  BusinessDirectory::select("bd_id","bd_nm")->WhereRaw('bd_status in (1,2) ')->get();
        $bdRemap = [];
        $bdRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($bd as $val){
            $bdRemap[]=["id"=>$val->bd_id, "name" =>  $val->bd_nm  ];
        }
        $collection['bd_id'] = json_decode(json_encode($bdRemap));

		$this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","voucher-partner")->first());
    }

}