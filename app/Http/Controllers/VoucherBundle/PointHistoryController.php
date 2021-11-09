<?php

namespace App\Http\Controllers\VoucherBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\VoucherModel\PointHistory;
use App\Http\Model\VoucherModel\PointScenario;
class PointHistoryController extends ScafoldController
{
   
    public function __construct(){

        $model = new PointHistory;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        $this->setTypeOfField("ps_id","hidden");
        $this->setTypeOfField("ph_name",null,"skenario");
        $this->setTypeOfField("created_date","text-datepicker","tanggal_event");
        $this->setTypeOfField("ph_before_point","text","point_awal");
        $this->setTypeOfField("ph_after_point","text","point");
        $this->setTypeOfField("entities_id","hidden");
        $this->setTypeOfField("entities_type","text","tipe_event");
        $this->setTypeOfField("entity_name","text","nama_event");
       
        $pointScenario =  PointScenario::select( "ps_name")->WhereRaw('ps_status = 1')->get();
        $pointScenarioRemap = [];
        $pointScenarioRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($pointScenario as $val){
            $pointScenarioRemap[]=["id"=>$val->ps_name, "name" =>  $val->ps_name  ];
        }
        $collection['ph_name'] = json_decode(json_encode($pointScenarioRemap));

        $this->setFieldRelation("member_name",json_decode(json_encode(["name"=> "nama_anggota" , "type"=> "text", "data"=>[], "relations" => [ "member" , "member_nm" ] ])));

        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("member_id",json_decode(json_encode(["name"=> "member_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("member",json_decode(json_encode(["name"=> "member" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        
        $this->setCollections($collection);

        $this->setCaption(\App\Http\Model\SubMenu::where("code","point-history")->first());
    }

}