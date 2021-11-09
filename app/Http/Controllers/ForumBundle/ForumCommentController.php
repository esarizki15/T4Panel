<?php

namespace App\Http\Controllers\ForumBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ForumModel\ForumComment;
use App\Http\Model\MemberModel\Member;
use App\Http\Model\ForumModel\Forum;
class ForumCommentController extends ScafoldController
{
   
    public function __construct(){

        $model = new ForumComment;
        $this->setModelController($model);
        $this->setTypeOfField("fc_isi","text","komentar");
        $this->setTypeOfField("id","hidden");
 		$this->setTypeOfField("fc_id","hidden");
        $this->setTypeOfField("fc_date","hidden");
        $this->setTypeOfField("fc_last_update","hidden");
		$member =  Member::select("member_id","member_nm")->WhereRaw('member_status <> 9')->get();
        $memberRemap = [];
        $memberRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemap[]=["id"=>$val->member_id, "name" =>  $val->member_nm  ];
        }
        $collection['member_id'] = json_decode(json_encode($memberRemap));

		$forum =  Forum::select("forum_id","forum_nm")->get();
        $forumRemap = [];
        $forumRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($forum as $val){
            $forumRemap[]=["id"=>$val->forum_id, "name" =>  $val->forum_nm  ];
        }
        $collection['forum_id'] = json_decode(json_encode($forumRemap));


    	$this->setFieldRelation("member_name",json_decode(json_encode(["name"=> "nama_member" , "type"=> "text", "data"=>[], "relations" => [ "member" , "member_nm" ] ])));
        $this->setFieldRelation("member_id",json_decode(json_encode(["name"=> "member_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("member",json_decode(json_encode(["name"=> "member" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

    	$this->setFieldRelation("forum_name",json_decode(json_encode(["name"=> "nama_forum" , "type"=> "text", "data"=>[], "relations" => [ "forum" , "forum_nm" ] ])));
        $this->setFieldRelation("forum_id",json_decode(json_encode(["name"=> "forum_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("forum",json_decode(json_encode(["name"=> "forum" , "type"=> "hidden", "data"=>[], "relations" => [] ])));


		$this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","forum-comment")->first());
    }

}