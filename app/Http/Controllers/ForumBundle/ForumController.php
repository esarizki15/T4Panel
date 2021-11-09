<?php

namespace App\Http\Controllers\ForumBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ForumModel\Forum;
use App\Http\Model\MemberModel\Member;
use App\Http\Model\ForumModel\ForumCategory;
use App\Http\Model\MasterModel\Category;
class ForumController extends ScafoldController
{
   
    public function __construct(){

        $model = new Forum;
        $this->setModelController($model);

        $this->setTypeOfField("forum_desc","textarea","konten");
        $this->setTypeOfField("forum_nm","text","judul");
 		$this->setTypeOfField("forum_date","hidden");
 		$this->setTypeOfField("forum_id","hidden");
 		$this->setTypeOfField("forum_pinned","hidden");
 		$this->setTypeOfField("forum_last_update","hidden");
        $this->setTypeOfField("forum_img","file","gambar");
        $this->setTypeOfField("member_id",null,"author");
        $this->setTypeOfField("fk_id",null,"kategori");
        $this->setTypeOfField("kategori_id",null,"cluster");
        $this->setStoragePath(  env('FORUM_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('FORUM_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);

        $this->setTypeOfField("forum_status",null,"status");
    	$collection['forum_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );

		$member =  Member::select("member_id","member_nm")->WhereRaw('member_status <> 9')->get();
        $memberRemap = [];
        $memberRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemap[]=["id"=>$val->member_id, "name" =>  $val->member_nm  ];
        }
        $collection['member_id'] = json_decode(json_encode($memberRemap));

        $forumCategory =  ForumCategory::select("fk_id","fk_nm")->get();
        $forumCategoryRemap = [];
        $forumCategoryRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($forumCategory as $val){
            $forumCategoryRemap[]=["id"=>$val->fk_id, "name" =>  $val->fk_nm  ];
        }
        $collection['fk_id'] = json_decode(json_encode($forumCategoryRemap));

        $category =  Category::select("kategori_id","kategori_nm")->get();
        $categoryRemap = [];
        $categoryRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($category as $val){
            $categoryRemap[]=["id"=>$val->kategori_id, "name" =>  $val->kategori_nm  ];
        }
        $collection['kategori_id'] = json_decode(json_encode($categoryRemap));

		$this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","forum")->first());
    }
}