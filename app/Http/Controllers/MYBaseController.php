<?php

namespace App\Http\Controllers;

use  Illuminate\Support\Facades\Route;

class MYBaseController extends Controller
{   
    var $captionScafold ; 
    public $users ;
    public $self ;
    public function setCaption($caption= null){
          if( $caption == null ) $caption = \App\Http\Model\SubMenu::where("code","__")->first();
          $this->self = $this;
          $this->captionScafold = $caption ;
          $this->middleware('auth');
          $this->middleware(function ($request, $next) {
                return  $this->self->getPermission($request, $next);
            
        });
          
         
    }

    public function getCaption(){
         if($this->captionScafold) return $this->captionScafold ;
         else return \App\Http\Model\SubMenu::where("code","__")->first();
    }

    public function getPermission($request, $next){
          $submenu = $this->self->captionScafold;
               if(!in_array($submenu->code,['__','dashboard'] ) ){
      
              if(!array_key_exists($submenu->id,json_decode(\Auth::user()->accessmenu->where('sub_menu_id','<>','')->groupBy('sub_menu_id'),true) ) )  {
                if($submenu->path == "" ) {
                  $response['data'] = 0;
                  $response['result'] = false;
                   return response()->json($response);
                } else {
                    return redirect()->route('home');
                }

              }
          }
          return $next($request);
    }

    public function getNavigations(){
      $navigation =   \App\Http\Model\Navigation::orderBy('order','asc')->get();
 
        $navigation->filter(function ($nav) {
            $menus = $nav->menu->filter(function($menu){

              $submenus = $menu->submenu->filter(function($submenu){
                      $granted = false;
                    if(array_key_exists($submenu->id,json_decode(\Auth::user()->accessmenu->where('sub_menu_id','<>','')->groupBy('sub_menu_id'),true) ) )     $granted = true;
                    return $granted;

               });

              $menu->submenu = $submenus;
            
              return $menu->submenu->count();

            });
            $nav->menu = $menus;
            return  $nav->menu->count();
        });
        return $navigation;
    }
    

    public function view($a,$b=array()){    

        $b['caption'] = $this->getCaption();
        $b['navs'] = $this->getNavigations();
        
        return view($a,$b);

    }
}
