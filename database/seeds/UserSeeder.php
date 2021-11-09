<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Model\User;
use App\Http\Model\Navigation;
use App\Http\Model\UserRole;
use App\Http\Model\Menu;
use App\Http\Model\SubMenu;
use App\Http\Model\AclMenu;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "Admin Panel";
        $user->email = "admin@gmail.com";
        $user->password = bcrypt("rahasia");
        $user->username = "admin";
        $user->created_by = 1;
        $user->updated_by = 1;
        $user->fullname = "Admin Panel";
        $user->role_code = "SUPERUSER";
        $user->save();
        
        $navs = [
            [
                'name'=>"MAIN NAVIGATION",
                'code'=>"main-navi",
                'order'=> 1
            ],
            [
                'name'=>"ACL NAVIGATION",
                'code'=>"acl-navi",
                'order'=> 99
            ],
        ];
        foreach($navs as $nav){
            $navigation = new Navigation();
            $navigation->name = $nav["name"];
            $navigation->code = $nav["code"];
            $navigation->order = $nav["order"];
            $navigation->save();
        }
        
        $userRoles = [
            [
                "code"=>"VIEWER",
                "view"=> true,
                "create"=> false,
                "update"=> false,
                "delete"=> false,
                "super"=> false,
            ],
            [
                "code"=>"ADMIN",
                "view"=> true,
                "create"=> true,
                "update"=> true,
                "delete"=> true,
                "super"=> false,
            ],
            [
                "code"=>"SUPERUSER",
                "view"=> true,
                "create"=> true,
                "update"=> true,
                "delete"=> true,
                "super"=> true,
            ],
        ];
        foreach($userRoles as $userRole){
            $role = new UserRole();
            $role->code = $userRole["code"];
            $role->view = $userRole["view"];
            $role->create = $userRole["create"];
            $role->update = $userRole["update"];
            $role->delete = $userRole["delete"];
            $role->save();
        }

        $menus = [
            [
                'name'=>"User Access Management",
                'code'=>"acl-user",
                'status'=> true,
                'nav_id' => 2,
                'icon' => "fa-users",
                'order'=> 99
            ],
            [
                'name'=>"Menu Management",
                'code'=>"acl-menu",
                'status'=> true,
                'nav_id' => 2,
                'icon' => "fa-navicon",
                'order'=> 99
            ],
            [
                'name'=>"Main Menu",
                'code'=>"main",
                'status'=> true,
                'nav_id' => 1,
                'icon' => "fa-dashboard",
                'order'=> 1
            ],
        ];
        foreach($menus as $menu){
            $mn = new Menu();
            $mn->name = $menu["name"];
            $mn->code = $menu["code"];
            $mn->status = $menu["status"];
            $mn->nav_id = $menu["nav_id"];
            $mn->icon = $menu["icon"];
            $mn->order = $menu["order"];
            $mn->save();
        }

        $subMenus = [
            [
                'name'=>"User Access",
                'code'=>"access-user",
                'status'=> true,
                'menu_id' => 1,
                'path' => "access-users.index",
            ],
            [
                'name'=>"Menu",
                'code'=>"menu",
                'status'=> true,
                'menu_id' => 2,
                'path' => "menu.index",
            ],
            [
                'name'=>"Sub Menu",
                'code'=>"sub-menu",
                'status'=> true,
                'menu_id' => 2,
                'path' => "sub-menu.index",
            ],
            [
                'name'=>"Navigation",
                'code'=>"navi",
                'status'=> true,
                'menu_id' => 2,
                'path' => "navi.index",
            ],
            [
                'name'=>"Dashboard",
                'code'=>"dashboard",
                'status'=> true,
                'menu_id' => 3,
                'path' => "home",
            ],
            [
                'name'=>"User Profile",
                'code'=>"profile",
                'status'=> true,
                'menu_id' => 3,
                'path' => "profile.index",
            ],
        ];
        foreach($subMenus as $subMenu){
            $sm = new SubMenu();
            $sm->name = $subMenu["name"];
            $sm->code = $subMenu["code"];
            $sm->status = $subMenu["status"];
            $sm->menu_id = $subMenu["menu_id"];
            $sm->path = $subMenu["path"];
            $sm->save();
        }

        $aclMenus = [
            [
                "user_id" => 1,
                "menu_id" => 1,
                "sub_menu_id" => 1,
            ],
            [
                "user_id" => 1,
                "menu_id" => 2,
                "sub_menu_id" => 2,
            ],
            [
                "user_id" => 1,
                "menu_id" => 2,
                "sub_menu_id" => 3,
            ],
            [
                "user_id" => 1,
                "menu_id" => 2,
                "sub_menu_id" => 4,
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 5,
            ],
            [
                "user_id" => 1,
                "menu_id" => 3,
                "sub_menu_id" => 6,
            ],
        ];
        foreach($aclMenus as $aclMenu){
            $sm = new AclMenu();
            $sm->user_id = $aclMenu["user_id"];
            $sm->menu_id = $aclMenu["menu_id"];
            $sm->sub_menu_id = $aclMenu["sub_menu_id"];
            $sm->save();
        }
    }
}
