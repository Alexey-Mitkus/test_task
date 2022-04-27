<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ServiceDigitalIdeaController extends BaseController
{
    public function store(Request $request)
    {
        dd('index', $request->all());
    }
}