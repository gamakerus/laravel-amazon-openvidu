<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
date_default_timezone_set('America/Chicago');
class HomeController extends Controller
{
    public function index()
    {
        return view('frontend/home');
    }
}
