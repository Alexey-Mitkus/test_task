<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class NotificationController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = User::withTrashed()->with(['notifications'])->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first();

        SEOMeta::setTitle('Уведомления');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        return view('dashboard.notifications.index', compact('user'));
    }
}
