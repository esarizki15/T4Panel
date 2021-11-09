<?php

namespace App\Http\Controllers\VoucherBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\VoucherModel\VoucherUsage;
class VoucherUsageController extends ScafoldController
{
   
    public function __construct(){

        $model = new VoucherUsage;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        
        $this->setCaption(\App\Http\Model\SubMenu::where("code","voucher-usage")->first());
    }

}