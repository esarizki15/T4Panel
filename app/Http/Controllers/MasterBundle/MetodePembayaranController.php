<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\MetodePembayaran;
class MetodePembayaranController extends ScafoldController
{
   
    public function __construct(){

        $model = new MetodePembayaran;
        $this->setModelController($model);
		$collection['mp_status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED"  ] ] ) );
		 $this->setCollections($collection);

        $this->setTypeOfField("mp_id","hidden");
        $this->setTypeOfField("mp_logo","file");
        $this->setStoragePath(  env('PEMBAYARAN_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('PEMBAYARAN_ASSETS_PATH','') );
        $this->setThumbWidth(260);
        $this->setThumbHeight(194); 
        $this->setCaption(\App\Http\Model\SubMenu::where("code","metode-pembayaran")->first());
    }

}