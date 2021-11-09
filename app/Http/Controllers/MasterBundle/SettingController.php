<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\Setting;
class SettingController extends ScafoldController
{
   
    public function __construct(){

        $model = new Setting;
        $this->setModelController($model);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","setting")->first());
    }

}