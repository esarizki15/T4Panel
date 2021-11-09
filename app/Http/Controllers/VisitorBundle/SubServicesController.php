<?php

namespace App\Http\Controllers\VisitorBundle;

use Illuminate\Http\Request;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\AppointmentSubServicesModel;
use App\Http\Model\AppointmentModel\AppointmentServicesModel;
class SubServicesController extends ScafoldController
{
    var $moduleName = "visitor";
    var $clientType = "VISITOR";
    var $clientTypelabel = "VISITOR";

    public function __construct(){
        $model = new AppointmentSubServicesModel;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        $this->setTypeOfField("location_id","hidden");
        $this->setTypeOfField("text_box","hidden");
        $this->setTypeOfField("service_id",null,"service_name");
        $collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
        $appointmentService =  AppointmentServicesModel::select("id","service_name")->get();
        $appointmentServiceRemap = [];
        $appointmentServiceRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($appointmentService as $val){
            $appointmentServiceRemap[]=["id"=>$val->id, "name" =>  $val->service_name  ];
        }
        $collection['service_id'] = json_decode(json_encode($appointmentServiceRemap));
        

        $this->setCollections($collection);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","{$this->moduleName}-sub-services")->first());
    }
}