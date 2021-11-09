<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\TagihanSml;
class TagihanSmlController extends ScafoldController
{
   
    public function __construct(){

        $model = new TagihanSml;
        $this->setModelController($model);
        $this->setTypeOfField("id","hidden");

        $this->setCaption(\App\Http\Model\SubMenu::where("code","tagihan-sml")->first());
    }

}