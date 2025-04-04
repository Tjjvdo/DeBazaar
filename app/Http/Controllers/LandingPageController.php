<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function myLandingpage(){
        return view('landingpage.my-landingpage');
    }
}