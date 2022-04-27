<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Arr;

use Carbon\Carbon;
use App\Models\UserManagment;
use App\Models\UserManagmentRole;

class UserManagmentController extends BaseController
{
    public function roles(Request $request)
    {
        $roles = UserManagmentRole::all();
        return $this->sendResponse($roles);
    }
}
