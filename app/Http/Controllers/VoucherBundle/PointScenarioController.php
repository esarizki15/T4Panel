<?php

namespace App\Http\Controllers\VoucherBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\VoucherModel\PointScenario;
class PointScenarioController extends ScafoldController
{
   
    public function __construct(){

        $model = new PointScenario;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        $collection['ps_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
        $collection['ps_is_recur']= json_decode( json_encode([ ["id"=>"NO", "name"=> "NO" ] , ["id"=>"YES", "name"=> "YES" ]  ]) );
		$this->setCollections($collection);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","point-scenario")->first());
    }

}