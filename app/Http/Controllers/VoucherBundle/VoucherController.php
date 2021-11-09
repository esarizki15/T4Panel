<?php

namespace App\Http\Controllers\VoucherBundle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

use App\Http\Model\MemberModel\Member;
use App\Http\Model\VoucherModel\Voucher;
use App\Http\Model\VoucherModel\VoucherCategory;
class VoucherController extends ScafoldController
{
   
    public function __construct(){

        $model = new Voucher;
        $this->setModelController($model);

        $this->setTypeOfField("id","hidden");
        $this->setTypeOfField("term","hidden");
        $this->setTypeOfField("description","hidden");
          $member =  Member::select("member_id","member_nm")->WhereRaw('member_status <> 9')->get();
        $memberRemap = [];
        $memberRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($member as $val){
            $memberRemap[]=["id"=>$val->member_id, "name" =>  $val->member_nm  ];
        }
        $collection['member_id'] = json_decode(json_encode($memberRemap));

        // $this->setFieldRelation("member_name",json_decode(json_encode(["name"=> "member_name" , "type"=> "text", "data"=>[], "relations" => [ "member" , "member_nm" ] ])));
        $this->setFieldRelation("member_id",json_decode(json_encode(["name"=> "member_id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));
        $this->setFieldRelation("member",json_decode(json_encode(["name"=> "member" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

        $this->setTypeOfField("start_date","text-datepicker");
        $this->setTypeOfField("end_date","text-datepicker");
        $this->setTypeOfField("redeem_date","text-datepicker");
        

        // $this->setTypeOfField("description","hidden");
        $collection['amount_type']= json_decode( json_encode([ ["id"=>"FIXED", "name"=> "Fixed Amount" ] , ["id"=>"PERCENTAGE", "name"=> "Percentage" ] ]) );

        $collection['type']= json_decode( json_encode([ ["id"=>"PROMO", "name"=> "Promo" ]  ]) );

        $voucherCategory =  VoucherCategory::select("id","name")->WhereRaw('status = 1')->get();
        $voucherCategoryRemap = [];
        $voucherCategoryRemap[] = ["id"=>null,"name"=>"CHOOSE"];
        foreach($voucherCategory as $val){
            $voucherCategoryRemap[]=["id"=>$val->id, "name" =>  $val->name  ];
        }
        $collection['category'] = json_decode(json_encode($voucherCategoryRemap));

        $this->setDateRangeField("redeem_date");

        $this->setTypeOfField("image","file");
        $this->setTypeOfField("term_and_condition","textarea-wysihtml5");
        $this->setTypeOfField("how_to_use","textarea-wysihtml5");
        $this->setFieldRelation("id",json_decode(json_encode(["name"=> "id" , "type"=> "hidden", "data"=>[], "relations" => [] ])));

    	$collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "USER VOUCHER" ] , ["id"=>"2", "name"=> "PUBLIC VOUCHER" ]  , ["id"=>"3", "name"=> "REDEEMED VOUCHER" ]  ]) );
		$this->setCollections($collection);
        $this->setStoragePath(  env('VOUCHER_ASSETS_PATH','') );
        $this->setUrlPath( env('ASSETS_URL','') ."/".env('VOUCHER_ASSETS_PATH','') );
      	$this->setThumbWidth(260);
        $this->setThumbHeight(194);
        $this->setCaption(\App\Http\Model\SubMenu::where("code","voucher")->first());
    }

    public function index(Request $request) {
        $this->setTypeOfField("supplier_id","hidden");
 
        $user = \Auth::user();
        $grantType = $user->grantOption($user->role);
        $dateRangeField = $this->dateRangeField ;
      
        $param = $request->all(); 
        if(isset($param['create-qr'])) {
            if($param['create-qr'] == 'test') {
                $this->saveToPdfQr($param['merchant-data']);
            }
        }
        $model = $this->getModelController();
 
        $model = $model::select([
                \DB::raw("voucher.id as id"),
                \DB::raw("v_name as voucher_name"),
                \DB::raw("v_code as voucher_code"),
                \DB::raw("voucher_category.name as category_voucher"),
                \DB::raw("amount"),
                \DB::raw("case voucher.amount_type  
                when 'FIXED' then 'Fixed Amount' 
                when 'PERCENTAGE' then 'Percentage' 
                else 'NOT SPECIFIED' end as type_amount"),
                \DB::raw("case voucher.type  
                when 'PROMO' then 'Promo' 
                else 'NOT SPECIFIED' end as voucher_type"),
                \DB::raw("need_point"),
                \DB::raw("start_date"),
                \DB::raw("end_date"),
                \DB::raw("member.member_nm as fullname"),
                \DB::raw("image"),
                \DB::raw("redeem_date"),
                \DB::raw("case voucher.status  
                when 0 then 'UNPUBLISHED' 
                when 1 then 'USER VOUCHER' 
                when 2 then 'PUBLIC VOUCHER' 
                when 3 then 'REDEEMED VOUCHER' 
                else 'NOT SPECIFIED' end as status_voucher") , 
            ]);
        $model->leftJoin('member','member.member_id','=','voucher.member_id') ;
        $model->leftJoin('voucher_category','voucher_category.id','=','voucher.category') ;

        $assetsPath = $this->getUrlPath();
        $caption = $this->getCaption();
        
        $columns = json_decode(json_encode([ 
            [ "name" => "id" , "type" => "character varying" , "table" => "" , "where" => "voucher.id" ],
            [ "name" => "voucher_name" , "type" => "character varying" , "table" => "" , "where" => "voucher.v_name" ],
            [ "name" => "voucher_code" , "type" => "character varying" , "table" => "" , "where" => "voucher.v_code" ],
            [ "name" => "category_voucher" , "type" => "character varying" , "table" => "" , "where" => "voucher_category.name" ],
            [ "name" => "amount" , "type" => "character varying" , "table" => "" , "where" => "voucher.amount" ],
            [ "name" => "type_amount" , "type" => "character varying" , "table" => "" , "where" => "case voucher.amount_type  
                when 'FIXED' then 'Fixed Amount' 
                when 'PERCENTAGE' then 'Percentage' 
                else 'NOT SPECIFIED' end" ],
            [ "name" => "voucher_type" , "type" => "character varying" , "table" => "" , "where" => "case voucher.type  
                when 'PROMO' then 'Promo' 
                else 'NOT SPECIFIED' end" ],
            [ "name" => "need_point" , "type" => "character varying" , "table" => "" , "where" => "voucher.need_point" ],
            [ "name" => "start_date" , "type" => "character varying" , "table" => "" , "where" => "voucher.start_date" ],
            [ "name" => "end_date" , "type" => "character varying" , "table" => "" , "where" => "voucher.end_date" ],
            [ "name" => "fullname" , "type" => "character varying" , "table" => "" , "where" => "member.member_nm" ],
            [ "name" => "image" , "type" => "character varying" , "table" => "" , "where" => "voucher.image" ],
            [ "name" => "redeem_date" , "type" => "character varying" , "table" => "" , "where" => "voucher.redeem_date" ],
            [ "name" => "status_voucher" , "type" => "character varying" , "table" => "" , "where" => "case voucher.status  
                when 0 then 'UNPUBLISHED' 
                when 1 then 'USER VOUCHER' 
                when 2 then 'PUBLIC VOUCHER' 
                when 3 then 'REDEEMED VOUCHER' 
                else 'NOT SPECIFIED' end" ],
        ]));

        
        if(isset($param['fbfl'])) {
            $fbfl = $param['fbfl'];
        }else{
            $fbfl = '';
        }
        if(isset($param['q'])) {
            $strarr= [];
            $valarr= [];
            if(count($columns)>0){
                foreach($columns as $column){

                    if(in_array($column->type, [ "character varying" , "text"] ) ){
                        if( $fbfl == $column->name ) {
                            if( isset($column->where) && $column->where ) {
                                $strarr[] =  'LOWER('.$column->where.') LIKE ? ' ;
                            }else{
                                $strarr[] = ( ( isset($column->table ) && $column->table )  ? 'LOWER('.$column->table.'.'.$column->name.') LIKE ? ' : 'LOWER('.$column->name.') LIKE ? ' ) ;
                            }
                            $valarr[] = '%' . strtolower($param['q']) . '%';
                        }
                    }
                }
                if(count($strarr) >0 && $param['q']) {
                     $model->WhereRaw(implode(" OR ",$strarr),$valarr);
                }

            }
        }else{
            $param['q'] = '';
        }

        // if(isset($param['q'])) {
        //     $param['dtr'] = '';
        // }else{

            if(isset($param['dtr']) && isset( $param['search-by-date'] ) ) {
                if( $this->dateRangeField ) {
                    $dateRangeParamValueStart=date("Y-m-d");
                    $dateRangeParamValueEnd=date("Y-m-d");
                    $dateRangeFullRaw = explode(" - ",$param['dtr']);
                    if( count($dateRangeFullRaw) > 1) {
                        $dateRangeParamRawStart = explode("/",$dateRangeFullRaw[0]);
                        $dateRangeParamRawEnd = explode("/",$dateRangeFullRaw[1]);
                        $dateRangeParamPartStart[] = (isset($dateRangeParamRawStart[2]) ? $dateRangeParamRawStart[2] : "" );
                        $dateRangeParamPartStart[] = (isset($dateRangeParamRawStart[0]) ? $dateRangeParamRawStart[0] : "" );
                        $dateRangeParamPartStart[] = (isset($dateRangeParamRawStart[1]) ? $dateRangeParamRawStart[1] : "" );

                        $dateRangeParamPartEnd[] = (isset($dateRangeParamRawEnd[2]) ? $dateRangeParamRawEnd[2] : "" );
                        $dateRangeParamPartEnd[] = (isset($dateRangeParamRawEnd[0]) ? $dateRangeParamRawEnd[0] : "" );
                        $dateRangeParamPartEnd[] = (isset($dateRangeParamRawEnd[1]) ? $dateRangeParamRawEnd[1] : "" );

                        $useDateRangeValue = true;
                        foreach($dateRangeParamPartStart as $val){
                            if($val=="") {
                                $useDateRangeValue = false;
                            }
                        }
                        foreach($dateRangeParamPartEnd as $val){
                            if($val=="") {
                                $useDateRangeValue = false;
                            }
                        }
                        if($useDateRangeValue) {
                            $dateRangeParamValueStart = implode("-", $dateRangeParamPartStart)." 00:00:00";
                            $dateRangeParamValueEnd = implode("-", $dateRangeParamPartEnd)." 23:59:59";
                            $model = $model->where($this->dateRangeField,">=",$dateRangeParamValueStart)->where($this->dateRangeField,"<=",$dateRangeParamValueEnd);
                        }
                    } 
                }

            }else{
                $param['dtr'] = '';
            }
        // }

        $items = $model->orderBy("voucher.id","DESC");
        if(isset($param['export'])) {
            $items = $items->get();
        }else{
            $items = $items->paginate(10);

        }
        foreach($columns as &$column){
                $column->data = [];
                $column->type = "text";
                 if($this->itemCollection){
                    foreach($this->itemCollection as $key => $val){
                        if($column->name==$key) $column->data = $val;
                    }
                }
                if($this->typeOfFields){
                     foreach($this->typeOfFields as $key => $val){
                        if($column->name==$key) $column->type = $val; 
                    }
                }
        }

        $columnArr = [] ;
        foreach($columns as $val){
            $columnArr[$val->name] = $val;
        }
        if($this->fieldRelation){
            foreach($items as &$item){
                foreach($this->fieldRelation as $val){
                    $objectName = $val->name;
                    $columnArr[$val->name] = $val;
                    if(count($val->relations) > 0 ) {
                        $tempItems = $item;
                        foreach($val->relations as $relation){
                            if(isset($tempItems->$relation) ) {
                              $tempItems= $tempItems->$relation;
                            }
                        }
                        if(!is_object($tempItems)) $item->$objectName = $tempItems;  
                        else $item->$objectName = null; 
                    }  
                    
                }
               
            }
        } 
        $columns = $columnArr;

        if(isset($param['export'])) {
                $exports = new \App\Http\Controllers\ScafoldExport;
                $exports->setItems($items);
                $exports->setColumns($columns);
                return \Excel::download($exports,  $caption->code."-".date("YmdHis").".xls");
        }
        return $this->view('scafold-bundle.scafolding.index',compact('items', "caption", "param", "columns" , "assetsPath" , "grantType","dateRangeField" ) );

    }

    public function store(Request $request)
    {
        //todo :: image 1140 x 460
        $data = $request->all();
         foreach($request->all() as $key => $val){
            if ($request->hasFile($key)) {
                $this->validate($request, [
                    $key => 'bail|mimes:jpeg,png', 
                ]);
                $path = Storage::disk('public-api')->putFile($this->getStoragePath(), $request->file($key));
                if($path){
                        $data[$key] = $data[$key]->hashName();
                        $manager = new ImageManager(array('driver' => 'gd'));
                        $manager->make(Storage::disk('public-api')->path($path))->resize($this->getThumbWidth(), $this->getThumbHeight())->save(Storage::disk('public-api')->getDriver()->getAdapter()->getPathPrefix().$this->getStoragePath().'/thumb/'.$data[$key]);
                }
            }
         }
        $connectionname = $this->getModelController()->getConnectionName();
        $columns = \DB::connection($connectionname)->select( \DB::connection($connectionname)->raw(' 
        select column_name as name 
        from INFORMATION_SCHEMA.COLUMNS where table_name =\''.$this->getModelController()->getTable().'\' '));
        $columnArr = [];
        foreach($columns as $val){
            $columnArr[] = $val->name;
        }
         if(in_array('slug', $columnArr)) if(isset($data['name'])) $data['slug'] = slugify($data['name']);
        $data['supplier_id'] = 1;
        $msg = ' doesnt happened ';
        $data['v_code'] = (isset($data['v_code']) ? $data['v_code'] : strtoupper("VOS".str_pad("", 6, $this->generateRandomString(6), STR_PAD_LEFT)) ) ;
        try{
            $this->getModelController()::create($data);
            $msg = ' added successfully';
        }catch(\Illuminate\Database\QueryException  $e){
                    $msg = ' added failed '. $e->getMessage();
        }

        return redirect()->route( current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
       }

       function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function saveToPdfQr($merchantData=[]) {

        $qrCodes = [];
        $entities= json_decode($merchantData,true) ;

        if( isset($entities) && count($entities) > 0 ) { 
            include(__DIR__.'/../../../../app/Http/Middleware/phpqrcode/qrlib.php'); 
            $scenario = "SCAN_QR_DAILY";
            $entity = "EXTERNAL_MERCHANT";
            foreach($entities as $key => $val) {
                $encodeQr = json_encode([ "scenario" => $scenario , "entities_type" => $entity , "entities_id" => ($key+1) , "entities_name" => $val['name']  ]);
                $qrString =  $this->encryptThis($encodeQr);
                $qrCode =  $this->createQr($qrString, $val['name']) ;
                $filename = "QR_".str_replace(' ','_',$val['name']).".png";
                $qrCodes[] = $filename;
                $img = str_replace('data:image/png;base64,', '', $qrCode);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                file_put_contents(__DIR__."/../../../../public/qr-code/".$filename,$data);
            }


        }

        return $qrCodes;
   }

    function encryptThis($message="",$key="123!@#QWE") {

        if($message) {
            $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
            $iv = openssl_random_pseudo_bytes($ivlen);
            $ciphertext_raw = openssl_encrypt($message, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
            $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
            $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
            return $ciphertext;
        }

        return "";

    }

    function createQr($string="",$name=null ) {
  
            $dest = imagecreatetruecolor(640, 720);
            $white = imagecolorallocate($dest, 255, 255, 255);
            imagefill($dest, 0, 0, $white);
            if($name) {
                $fn_src = imagecreatetruecolor (1, 1);
                $black = imagecolorallocate($fn_src, 0 ,0, 0);
                $fnsrcWidth = imagesx($fn_src);
                $bbox = imagettfbbox(32, 0, __DIR__.'/../../../../public/fonts/Montserrat-Bold.ttf', $name);
                $x = (640 / 2) - ($bbox[4] / 2);
                imagettftext($dest, 32, 0, $x, 690, $black, __DIR__.'/../../../../public/fonts/Montserrat-Bold.ttf', $name); 
            }

            ob_start();
            \QRcode::png($string,false,QR_ECLEVEL_L,1,1);
            $rawQr = ob_get_contents();
            ob_end_clean();
            $src = imagecreatefromstring($rawQr);
            $srcWidth = imagesx($src);
            $srcHeight = imagesy($src);

            $im_src = imagecreatetruecolor (640, 640);
            imagealphablending($im_src, false);
            imagecopyresampled($im_src, $src, 0, 0, 0, 0, 640, 640, $srcWidth, $srcHeight);
            imagesavealpha($im_src, true);

            imagealphablending($dest, false);
            imagesavealpha($dest, true);
            imagecopymerge($dest, $im_src, 0, 0, 0, 0, 640, 640, 100); 
        
            ob_start();
            imagepng($dest);
            $qrCode2 = ob_get_contents();
            ob_end_clean();

            $qrCode = 'data:image/png;base64,'.base64_encode($qrCode2);
            
            imagedestroy($dest);
            return $qrCode;

    }

}