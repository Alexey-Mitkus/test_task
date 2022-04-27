<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationType;

class OrganizationController extends BaseController
{
    public function index(Request $request)
    {
        $organizations = Organization::search($request->get('search'))->orderBy('name', 'ASC')->get();
        return $this->sendResponse($organizations->toArray());
    }
}
