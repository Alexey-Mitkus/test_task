<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;

class IndexController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
    }

    public function index(Request $request)
    {
        SEOMeta::setTitle('Главная');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        return view('index');
    }
}