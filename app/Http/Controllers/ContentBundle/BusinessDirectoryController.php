<?php

namespace App\Http\Controllers\ContentBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ContentModel\BusinessDirectory;
use App\Http\Model\ContentModel\BusinessDirectoryCategory;
use App\Http\Model\MemberModel\Member;
class BusinessDirectoryController extends ScafoldController
{
   
    public function __construct(){

        $model = new BusinessDirectory;
        $this->setModelController($model);
        $this->setTypeOfField("bd_nm","text","nama");
        $this->setTypeOfField("bd_status",null,"status");
        $this->setTypeOfField("bd_desc","hidden-list","keterangan");
        $this->setTypeOfField("bdc_id",null,"kategori");
        $this->setTypeOfField("bd_pic","file","gambar_pemilik");
        $this->setTypeOfField("bd_image","file","gambar_toko");
        $this->setTypeOfField("bd_image_ktp","file","ktp");
        $this->setTypeOfField("bd_image_selfie","file","selfie_ktp");
        $this->setTypeOfField("bd_image_atmosphere","file","gambar_sekitar");
        $this->setTypeOfField("bd_image_additional","file","gambar_tambahan");
        $this->setTypeOfField("bd_image_sign","file","tanda_tangan");
        $this->setTypeOfField("bd_phone","text","telepon");
        $this->setTypeOfField("bd_long","text","longitude");
        $this->setTypeOfField("bd_lat","text","latitude");

        $this->setTypeOfField("bd_type","hidden");
        $this->setTypeOfField("member_id",null,"nama_pemilik");
        $this->setTypeOfField("bd_id","label","market_id");
        $this->setTypeOfField("bd_date","hidden");

        $this->setTypeOfField("bd_jam_open","text-timepicker","jam_buka");
        $this->setTypeOfField("bd_jam_close","text-timepicker","jam_tutup");

        // $this->setTypeOfField("bd_desc","textarea-wysihtml5");

        $this->setTypeOfField("bd_date_approve","text-datepicker");

        $this->setTypeOfField("bd_address","hidden-list","alamat");
        $this->setTypeOfField("bd_socmed_instagram","hidden-list","instagram");
        $this->setTypeOfField("bd_socmed_twitter","hidden-list","twitter");
        $this->setTypeOfField("bd_socmed_facebook","hidden-list","facebook");
        $this->setTypeOfField("bd_socmed_youtube","hidden-list","youtube");
        $this->setTypeOfField("bd_hari_note","hidden-list","keterangan_hari");
        $this->setTypeOfField("bd_jam_note","hidden-list","keterangan_jam");
        $this->setTypeOfField("bd_sosmed","hidden-list","social_media");
        $this->setTypeOfField("bd_hari","hidden-list","hari");
        $this->setTypeOfField("bd_note","hidden-list","catatan");

        $this->setTypeOfField("bd_merchant_id","hidden");


        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
    	$collection['bd_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "REMOVE" ] , ["id"=>"2", "name"=> "DIRECTORY"] ,  ["id"=>"1", "name"=> "OPEN"], ["id"=>"3", "name"=> "CLOSE" ]  ]) );
        $collection['bd_hari']= json_decode( json_encode([ ["id"=>"0", "name"=> "Not Set Yet" ] , ["id"=>"1", "name"=> "All Day"] ,  ["id"=>"2", "name"=> "Other"] ]) );
	   	$member =  Member::select("member_id","member_nm")->WhereRaw('member_status <> 9')->get();
        $memberRemap = [];
        $memberRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemap[]=["id"=>$val->member_id, "name" =>  $val->member_nm  ];
        }
        $collection['member_id'] = json_decode(json_encode($memberRemap));

   		$bdc =  BusinessDirectoryCategory::select("bdc_id","bdc_nm")->get();
        $bdcRemap = [];
        $bdcRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($bdc as $val){
            $bdcRemap[]=["id"=>$val->bdc_id, "name" =>  $val->bdc_nm  ];
        }
        $collection['bdc_id'] = json_decode(json_encode($bdcRemap));

		$this->setCollections($collection);
        $this->setFieldRelation("member_name",json_decode(json_encode(["name"=> "nama_pemilik" , "type"=> "text", "data"=>[], "relations" => [ "member" , "member_nm" ] ])));
  		$this->setFieldRelation("member_id",json_decode(json_encode(["name"=> "member_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("member",json_decode(json_encode(["name"=> "member" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

        $this->setStoragePath(  env('BUSINESS_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('BUSINESS_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","business-directory")->first());
    }

}