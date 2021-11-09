<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\ScafoldController;
use Illuminate\Http\Request;


class MenuController extends ScafoldController
{
      public function __construct(){
        $model = new \App\Http\Model\Menu;
        $this->setModelController($model); 
        $this->setCaption(\App\Http\Model\SubMenu::where("code","menu")->first());
        $collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );

        $collection['nav_id'] = \App\Http\Model\Navigation::select("id","name")->get();
        $this->setCollections($collection);



    }
   

}
