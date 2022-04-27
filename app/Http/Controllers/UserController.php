<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        SEOMeta::setTitle('Моя страница');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        return view('users.index', compact('user'));
    }

    public function documents(Request $request)
    {
        $user = Auth::user();

        SEOMeta::setTitle('Мои документы');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        return view('users.documents.index', compact('user'));
    }

    public function notifications(Request $request)
    {
        $user = Auth::user();

        SEOMeta::setTitle('Уведомления');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        return view('users.notifications.index', compact('user'));
    }
}
