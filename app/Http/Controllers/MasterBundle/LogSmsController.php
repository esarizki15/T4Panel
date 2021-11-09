<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\LogSms;
class LogSmsController extends ScafoldController
{
   
    public function __construct(){

        $model = new LogSms;
        $this->setModelController($model);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","log-sms")->first());
    }

}