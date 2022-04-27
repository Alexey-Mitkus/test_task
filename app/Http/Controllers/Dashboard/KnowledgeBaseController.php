<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        SEOMeta::setTitle('База знаний');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        
        return view('dashboard.knowledge-base.index', compact('user'));
    }
}
