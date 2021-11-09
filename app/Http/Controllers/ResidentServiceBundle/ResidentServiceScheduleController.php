<?php

namespace App\Http\Controllers\ResidentServiceBundle;

use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScafoldController;

use App\Http\Model\AppointmentModel\AppointmentScheduleModel;
use App\Http\Model\AppointmentModel\AppointmentServicesModel;
use App\Http\Model\AppointmentModel\AppointmentServiceLocationModel;

use App\Http\Model\AppointmentModel\AppointmentDaysOffModel;
use App\Http\Model\AppointmentModel\AppointmentScheduleTimeModel;

class ResidentServiceScheduleController extends ScafoldController
{
  public function __construct(){
      $model = new AppointmentScheduleModel;
      $this->setModelController($model);

      $this->setTypeOfField("id","hidden");
      $this->setDateRangeField("appointment_schedule_date");
      $this->setTypeOfField("appointment_service_id",null,"service_name");
      $this->setTypeOfField("appointment_schedule_date","text-datepicker","date");
      $this->setTypeOfField("appointment_schedule_time","text-timepicker","time");
      $this->setTypeOfField("appointment_location_id",null,"location_name");
      
      $appointmentService =  AppointmentServicesModel::select("id","service_name")->get();
      $appointmentServiceRemap = [];
      $appointmentServiceRemap[] = ["id"=>null,"name"=>"CHOOSE"];
      foreach($appointmentService as $val){
          $appointmentServiceRemap[]=["id"=>$val->id, "name" =>  $val->service_name  ];
      }
      $collection['appointment_service_id'] = json_decode(json_encode($appointmentServiceRemap));

      $appointmentLocation =  AppointmentServiceLocationModel::select("id","location_name")->get();
      $appointmentLocationRemap = [];
      $appointmentLocationRemap[] = ["id"=>null,"name"=>"CHOOSE"];
      foreach($appointmentLocation as $val){
          $appointmentLocationRemap[]=["id"=>$val->id, "name" =>  $val->location_name  ];
      }
      $collection['appointment_location_id'] = json_decode(json_encode($appointmentLocationRemap));

      $collection['status']= json_decode( json_encode([ ["id"=>"0", "name"=> "UNPUBLISHED" ] , ["id"=>"1", "name"=> "PUBLISHED" ]  ]) );
      $collection['is_resident']= json_decode( json_encode([ ["id"=>"0", "name"=> "PUBLIC" ] , ["id"=>"1", "name"=> "RESIDENT" ]  ]) );
      $this->setCollections($collection);
      
      $this->setCaption(\App\Http\Model\SubMenu::where("code","residentservice-schedule")->first());
  }
  
  public function index(Request $request){
    $this->setTypeOfField("supplier_id","hidden");
    $param = $request->all();
    $user = \Auth::user();
    $grantType = $user->grantOption($user->role);
    $model = new AppointmentServiceLocationModel;
    $model = $model::select('*');
    $model->where('status', '=', 1);
    $model->where('client_type', '=', 'TREATMENT_SERVICE');
    $items = $model->get();

    foreach ($items as &$item){
      $location = $item->id;
      
      $days_off = AppointmentDaysOffModel::select("*")->where('appointment_location_id', '=', $location)->get();
      $schedule = AppointmentScheduleTimeModel::select("*")->where('appointment_location_id', '=', $location)->get();

      $item->data = array(
        // "location" => $item,
        "days_off" => $days_off,
        "schedule" => $schedule
      );
    }

    // dd($items);
    
    return $this->view('appointment-bundle.appointment-schedule.index',compact('items') );
  }

  function createTimeRange($startTime, $endTime, $interval = '15 mins', $format = '12') {
    try {
   
      if(!isset($startTime) || !isset($endTime)) {
        throw new exception("Start or End time is missing");
      }
   
      $startTime = strtotime($startTime); 
      $endTime   = strtotime($endTime);
   
      $currentTime   = time(); 
      $addTime   = strtotime('+'.$interval, $currentTime); 
      $timeDiff      = $addTime - $currentTime;
   
      $timesArr = array();
      $timeFormat = ($format == '12')?'g:i:s A':'G:i:s'; 
      while ($startTime < $endTime) { 
          $timesArr[] = date($timeFormat, $startTime); 
          $startTime += $timeDiff; 
      }  
      return $timesArr;
    } catch (Exception $e) {
      return $e->getMessage();
   }
  }
  public function createSchedule($date, $location, $start, $end){
    $model = new AppointmentScheduleModel;
    $services = new AppointmentServicesModel;
    $services = $services->select('*')->where('status', '=', '1')->get();

    $date = new \DateTime($date);
    $date = $date->format("Y-m-d");
    // $date = date_format(date_create($date), 'Y-m-d');
    $time = $this->createTimeRange($start, $end, '30 mins', '24');

    // dd($time);

    $insertData = array();

    foreach ($services as $service){

      foreach ($time as $t){
        $insertData[] = [
          "appointment_service_id" => $service->id,
          "appointment_schedule_date" => "'{$date}'",
          "appointment_schedule_time" => $t,
          "status" => 1,
          "quota" => 3,
          "appointment_location_id" => $location
        ];
      }      
    }
    // dd($insertData);
    $model->insert($insertData);
  }

  public function store(Request $request){
    $msg = "UNK";
    try {
      $param = $request->all(); //dd($param);

      $req_type = $param['submit']['type'];
      $location = $param['submit']['location'];
  
      if ($req_type == "days_off"){
        $submit_data = $param['submit'];
  
        if (isset($submit_data['days_off']['new'])){ $msg = "CREATE DO";
          foreach ($submit_data['days_off']['new'] as $new){ 
            AppointmentDaysOffModel::insert([
              "date" => $new['date'],
              "appointment_location_id" => $location
            ]);
          }
        }

        if (isset($submit_data['days_off']['old'])){ $msg = "UPDATE DO";
          foreach ($submit_data['days_off']['old'] as $key_old => $val_old){
            AppointmentDaysOffModel::where('id', '=', $key_old)->update([
              "date" => \DB::raw("TO_DATE('".date_format(date_create($val_old['date']), "Y-m-d")."','YYYY-MM-DD')")
              // "date" => \DB::raw("DATE '" . date_format(date_create($val_old['date']), "Y-d-m") . "'")
            ]);
          }
        }

        if (isset($submit_data['removed'])){ $msg = "REMOVE DO";
          foreach ($submit_data['removed'] as $removed){
            AppointmentDaysOffModel::where('id', '=', $removed['id'])->delete();
          }
        }
      }

      //

      if ($req_type == "schedule"){
        $submit_data = $param['submit'];

        if (isset($submit_data['schedule']['new'])){ $msg = "CREATE SCH";
          foreach ($submit_data['schedule']['new'] as $new){
            AppointmentScheduleTimeModel::insert([
              "time_start" => date('H:i', strtotime($new['time_start'])),
              "time_end" => date('H:i', strtotime($new['time_end'])),
              "quota" => $new['quota'],
              "appointment_location_id" => $location
            ]);
          }
        }

        if (isset($submit_data['schedule']['old'])){ $msg = "UPDATE SCH";
          foreach ($submit_data['schedule']['old'] as $key_old => $val_old){
            AppointmentScheduleTimeModel::where('id', '=', $key_old)->update([
              "time_start" => date('H:i', strtotime($val_old['time_start'])),
              "time_end" => date('H:i', strtotime($val_old['time_end'])),
              "quota" => $val_old['quota']
            ]);
          }
        }

        if (isset($submit_data['removed'])){ $msg = "REMOVE SCH";
          foreach ($submit_data['removed'] as $removed){
            AppointmentScheduleTimeModel::where('id', '=', $removed['id'])->delete();
          }
        }
      }

      $msg = "Updated successfully";
    } catch (\Exception $ex) {
      return redirect()->route( current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg . ' ' .$ex->getMessage() ]);
    }

    return redirect()->route( current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);

  }

  public function destroy($date){
    $msg = "UNK";
    try {
      $model_schedule = new AppointmentScheduleModel;
      $model_schedule = $model_schedule->where('appointment_schedule_date', '=', $date)->delete();

      $msg = "Delete successfully";
    } catch (\Exception $ex) {
      $msg = "Invalid date!";
    }
    return redirect()->route( current(explode('.', Route::currentRouteName())) . '.index')->with(['message' => $msg ]);
  }
}
