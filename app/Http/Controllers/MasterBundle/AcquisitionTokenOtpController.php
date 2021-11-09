<?php

namespace App\Http\Controllers\MasterBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\MasterModel\Setting;
class AcquisitionTokenOtpController extends ScafoldController
{
   
    public function __construct(){

        $model = new Setting;
        $this->setModelController($model);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","acquisition-token-otp")->first());
    }

  	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $this->setTypeOfField("supplier_id","hidden");
        $param = $request->all();
        $user = \Auth::user();
        $grantType = $user->grantOption($user->role);
        $model = $this->getModelController(); 
        $primaryKey = $model->getKeyName();
        $model = $model::select('*');
        $model = $model->where('setting_nm','acquisition_token');
        $items = $model->orderBy($primaryKey,"DESC");
        $items = $items->first();
        $assetsPath = $this->getUrlPath();
        $caption = $this->getCaption();
        if(!$items) {
        	$items = json_decode(json_encode(["setting_desc"=>"Valid Date" , "setting_input"=>'17770101' , "setting_val_str"=>'Not Set Yet']));
        }
        return $this->view('master-bundle.acquisition-token-otp.index',compact('items', "caption", "param", "assetsPath" , "grantType" ) );
    }

    public function generateTokenAcquisition(){
    	$settingName = 'acquisition_token';
    	$dateValid = date('Ymd');
    	$randomToken = $this->generateRandomString();
        $model = $this->getModelController(); 
        $model = $model->where('setting_nm','acquisition_token')->first();

        $data['setting_val_str']=$randomToken;
        $data['setting_input']=$dateValid;

    	$msg = ' doesnt happened ';
        if( !$model ) {
        	$data['setting_nm'] = $settingName;
        	$data['setting_desc'] = "Valid Date";
	        try{
	            $this->getModelController()::create($data);
	            	$msg = 'generate OTP successfully';
	        }catch(\Illuminate\Database\QueryException  $e){
	                    $msg = ' Generate OTP failed '. $e->getMessage();
	        }
        }else {
	        try{
	            	$msg = 'generate OTP successfully';
	               $stat = $model->update($data);

	         }catch(\Illuminate\Database\QueryException  $e){
	                    $msg = ' Generate OTP failed '. $e->getMessage();
	        }
        }

        return redirect()->route(current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
    }

    function generateRandomString($length = 4) {
	    $characters = '0123456789';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

}

