<?php

namespace App\Http\Controllers\ProductBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\ProductModel\Order;
class OrderController extends ScafoldController
{
   
    public function __construct(){

        $model = new Order;
        $this->setModelController($model);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","order")->first());
    }

}