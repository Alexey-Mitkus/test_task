<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;

use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
    }

    public function index(Request $request)
    {
        SEOMeta::setTitle('Цифровые сервисы');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        
        $user = Auth::user();       

        return view('services.index', compact('user'));
    }

}
