<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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

        SEOMeta::setTitle('Пользователи');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        $users = User::with(['fields', 'job', 'job.organization', 'managment', 'managment.role', 'educations', 'educations.university', 'educations.type', 'roles', 'permissions'])->get()->filter(function($member){
            return $member->setAppends(['rating', 'avatar'])->withFields();
        })->values()->all();

        return view('dashboard.users.index', compact('user', 'users'));
    }
}
