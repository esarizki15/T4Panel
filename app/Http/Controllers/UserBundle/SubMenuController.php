<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\ScafoldController;
use Illuminate\Http\Request;


class SubMenuController extends ScafoldController
{
      public function __construct(){
        $model = new \App\Http\Model\SubMenu;
        $this->setModelController($model); 
        $this->setCaption(\App\Http\Model\SubMenu::where("code","sub-menu")->first());

        $collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );

        $collection['menu_id'] = \App\Http\Model\Menu::select("id","name")->get();
        $this->setCollections($collection);

    }
   

}
