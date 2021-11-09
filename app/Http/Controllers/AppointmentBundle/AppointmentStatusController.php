<?php

namespace App\Http\Controllers\AppointmentBundle;

use Illuminate\Http\Request;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\AppointmentStatusModel; 
class AppointmentStatusController extends ScafoldController
{
    public function __construct(){
        $model = new AppointmentStatusModel;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        $this->setTypeOfField("image_url","file");
        $this->setTypeOfField("no_content_image","file");
        $collection['is_active']= json_decode( json_encode([ ["id"=>"0", "name"=> "NO" ] , ["id"=>"1", "name"=> "YES" ]  ]) );
        $collection['show']= json_decode( json_encode([ ["id"=>"0", "name"=> "NO" ] , ["id"=>"1", "name"=> "YES" ]  ]) );
        $collection['use_cluster']= json_decode( json_encode([ ["id"=>"0", "name"=> "NO" ] , ["id"=>"1", "name"=> "YES" ]  ]) );
        $collection['use_service']= json_decode( json_encode([ ["id"=>"0", "name"=> "NO" ] , ["id"=>"1", "name"=> "YES" ]  ]) );
        $collection['use_email']= json_decode( json_encode([ ["id"=>"0", "name"=> "NO" ] , ["id"=>"1", "name"=> "YES" ]  ]) );
        $collection['client_type']= json_decode( json_encode([ 
            ["id"=>"CUSTOMER_SERVICE", "name"=> "CUSTOMER SERVICE" ] , 
            ["id"=>"CLUB_HOUSE", "name"=> "CLUB HOUSE" ] , 
            ["id"=>"VACCINE_REGISTRATION", "name"=> "VACCINE REGISTRATION" ] , 
            ["id"=>"VACCINE_REGISTRATION_CHILD", "name"=> "VACCINE REGISTRATION CHILD" ] , 
            ["id"=>"RESIDENT_SERVICE", "name"=> "RESIDENT SERVICE" ] , 
            ["id"=>"VISITOR", "name"=> "VISITOR" ] , 
            ["id"=>"CLUSTER_COMPLAIN", "name"=> "CLUSTER COMPLAIN" ] , 
            ["id"=>"TICKETING", "name"=> "TICKETING" ] , 
            ["id"=>"DOCUMENT_PERMISSION", "name"=> "DOCUMENT PERMISSION" ] , 
            ["id"=>"HAND_OVER", "name"=> "HAND OVER" ] , 
        ]) );
        $this->setCollections($collection);
        $this->setStoragePath(  env('APPOINTMENT_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('APPOINTMENT_ASSETS_PATH','') );
        $this->setThumbWidth(260);
        $this->setThumbHeight(194);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","appointment-status")->first());
    }
}