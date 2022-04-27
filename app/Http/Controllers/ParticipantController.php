<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class ParticipantController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        SEOMeta::setTitle('Участники');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        $users = User::with(['fields', 'job', 'job.organization', 'managment', 'managment.role', 'educations', 'educations.university', 'educations.type', 'roles', 'permissions'])->get()->filter(function($member){
            return $member->setAppends(['rating', 'avatar'])->withFields()->rating >= 10;
        })->values()->all();

        return view('participants.index', compact('user', 'users'));
    }

    public function show(User $user, Request $request)
    {
        $AuthUser = Auth::user();
        
        SEOMeta::setTitle('Страница участника');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        $user = User::with(['job', 'job.organization', 'managment', 'managment.role', 'educations', 'educations.university', 'educations.type', 'roles', 'permissions'])->where('id', $user->id)->first()->withFields();
        
        $status = 'viewer';

        if( $AuthUser->id == $user->id )
        {
            $status = 'user';
        }

        if( $AuthUser->hasRole('admin') )
        {
            $status = 'admin';
        }

        return view('participants.show', compact('user', 'status', 'AuthUser'));
    }
}
