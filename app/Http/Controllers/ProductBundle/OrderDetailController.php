<?php

namespace App\Http\Controllers\ProductBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ProductModel\OrderDetail;
class OrderDetailController extends ScafoldController
{
   
    public function __construct(){

        $model = new OrderDetail;
        $this->setModelController($model);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","order-detail")->first());
    }

}