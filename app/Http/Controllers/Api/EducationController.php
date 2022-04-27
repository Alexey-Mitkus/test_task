<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;

use App\Filters\EducationFilter;
use App\Models\Education;
use App\Models\EducationCategory;

class EducationController extends BaseController
{
    public function index(EducationFilter $filter, Request $request)
    {

        if( $request->missing('orderBy') )
        {
            $request->merge([
                'orderBy' => 'name'
            ]);
        }
        
        if( $request->filled('limit') )
        {
            $educations = Education::filter($filter)->paginate(($request->has('limit') ? (int) $request->input('limit') : 10));
        }else{
            $educations = Education::filter($filter)->get();
        }
        
        return $this->sendResponse($educations->toArray());
    }

    public function categories(Request $request)
    {
        $categories = EducationCategory::orderBy('name', 'ASC')->get();
        return $this->sendResponse($categories->toArray());
    }
}
