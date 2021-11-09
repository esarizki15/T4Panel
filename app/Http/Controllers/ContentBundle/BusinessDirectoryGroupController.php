<?php

namespace App\Http\Controllers\ContentBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ContentModel\BusinessDirectory;
use App\Http\Model\ContentModel\BusinessDirectoryGroup;
class BusinessDirectoryGroupController extends ScafoldController
{
   
    public function __construct(){

        $model = new BusinessDirectoryGroup;
        $this->setModelController($model); 
        $this->setTypeOfField('created_date',"hidden");
        $this->setTypeOfField('bd_id',null,"toko");
        $this->setTypeOfField('parent_bd_id',null,"pasar");

        $businessDirectory =  BusinessDirectory::select("bd_id","bd_nm")->WhereRaw('bd_status in (1,2)')->get();
        $businessDirectoryRemap = [];
        $businessDirectoryRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($businessDirectory as $val){
            $businessDirectoryRemap[]=["id"=>$val->bd_id, "name" =>  $val->bd_nm  ];
        }
        $collection['bd_id'] = json_decode(json_encode($businessDirectoryRemap));
        $collection['parent_bd_id'] = json_decode(json_encode($businessDirectoryRemap));

        $this->setCollections($collection);

        $this->setFieldRelation("businessDirectory",json_decode(json_encode(["name"=> "businessDirectory" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("businessDirectoryParent",json_decode(json_encode(["name"=> "businessDirectory" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

        $this->setCaption(\App\Http\Model\SubMenu::where("code","business-directory-group")->first());
    }

}