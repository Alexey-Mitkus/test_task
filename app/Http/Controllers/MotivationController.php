<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MotivationController extends Controller
{
    //
    public function index() {
        return view('motivation.index');
    }
}
