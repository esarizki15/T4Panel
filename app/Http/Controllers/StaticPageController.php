<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller ; 

class StaticPageController extends Controller
{ 

       public function index(Request $request){

              return view('i-glow' );

       }
    
       public function privacyPolicy(Request $request){

              return view('privacy-policy' );

       }
   

     
    
}
