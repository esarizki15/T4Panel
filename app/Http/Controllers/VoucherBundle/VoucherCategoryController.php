<?php

namespace App\Http\Controllers\VoucherBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\VoucherModel\VoucherCategory;
class VoucherCategoryController extends ScafoldController
{
   
    public function __construct(){

        $model = new VoucherCategory;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
    	$collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
		$this->setCollections($collection);
        
        $this->setCaption(\App\Http\Model\SubMenu::where("code","voucher-category")->first());
    }

}