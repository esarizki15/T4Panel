<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\Pages;
class PagesController extends ScafoldController
{
   
    public function __construct(){

        $model = new Pages;
        $this->setModelController($model);
        $this->setTypeOfField("page_id","hidden");
        $this->setTypeOfField("page_isguest","hidden");
        $this->setTypeOfField("page_description","hidden");
        $this->setTypeOfField("page_keywords","hidden");

        $this->setTypeOfField("page_content","textarea-wysihtml5");

        $this->setCaption(\App\Http\Model\SubMenu::where("code","page-static")->first());
    }

}