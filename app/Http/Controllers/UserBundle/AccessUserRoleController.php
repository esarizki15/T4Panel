<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\ScafoldController;
use Illuminate\Http\Request;


class AccessUserRoleController extends ScafoldController
{
    public function __construct(){
        $model = new \App\Http\Model\AclMenu;
        $this->setModelController($model); 
        $this->setCaption(\App\Http\Model\SubMenu::where("code","__")->first());
    }
   

    public function remove(Request $request)
    {
        $data =$request->all();
        $result = \App\Http\Model\AclMenu::where('user_id',$data['user_id'])
        ->where('menu_id',$data['menu_id'])
        ->where('sub_menu_id',$data['sub_menu_id'])
        ->delete();
        $response = false;
        if($result==1){
            $response = true;
        }
        return response()->json($response);
    }

    public function add(Request $request)
    {   
        $data =  $request->all();
        $model = $this->getModelController();
        $model->user_id = $data['user_id']; 
        $model->menu_id = $data['menu_id']; 
        $model->sub_menu_id = $data['sub_menu_id']; 
            $response['data'] = 0;
            $response['result'] = false;
        if($model->save()){
            $response['data'] = $model;
            $response['data']['menu'] = $model->menu;
            $response['data']['submenu'] = $model->submenu;
            $response['result'] = true;
        }
        return response()->json($response);

    }

}
