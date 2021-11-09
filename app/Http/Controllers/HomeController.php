<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends MYBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->setCaption(\App\Http\Model\SubMenu::where("code","dashboard")->first());
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('home');
    }

    public function custom_number_format($n, $precision = 0) {
        if ($n < 100000) {
            // Anything less than a million
            $n_format = number_format($n);
        } else if ($n < 1000000) {
            // Anything less than a billion
            $n_format = number_format($n / 1000, $precision) . ' Rb';
        } else if ($n < 1000000000) {
            // Anything less than a billion
            $n_format = number_format($n / 1000000, $precision) . ' Jt';
        } else {
            // At least a billion
            $n_format = number_format($n / 1000000000, $precision) . ' Mi';
        }

        return $n_format;
    }

  
}
