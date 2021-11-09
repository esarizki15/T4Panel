<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\ScafoldController;
use Illuminate\Http\Request;


class NavigationController extends ScafoldController
{
      public function __construct(){
        $model = new \App\Http\Model\Navigation;
        $this->setModelController($model); 
        $this->setCaption(\App\Http\Model\SubMenu::where("code","navi")->first());
    }
   

}
