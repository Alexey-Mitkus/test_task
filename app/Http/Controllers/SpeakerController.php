<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Arr;

class SpeakerController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
    }

    public function index(Request $request)
    {
        SEOMeta::setTitle('Здравые мысли');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        
        return view('speakers.index');
    }

    public function store(Request $request)
    {
		$themes = collect([]);
		
		if( $request->filled('theme1') )
		{
			$themes->push($request->input('theme1'));
		}

		if( $request->filled('theme2') )
		{
			$themes->push($request->input('theme2'));
		}

		if( $request->filled('theme3') )
		{
			$themes->push($request->input('theme3'));
		}

		if( $request->filled('theme4') )
		{
			$themes->push($request->input('theme4'));
		}

        if( $themes->count() )
        {
            $request->merge([
                'themes' => $themes->toArray()
            ]);
        }
        
        // \Notification::route('mail', env('MAIL_ADMINISTRATOR_EMAIL'))->notify(new \App\Notifications\NewSpeakerNotification(Arr::except($request->all(), ['_token'])));
        \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->notify(new \App\Notifications\NewSpeakerNotification(Arr::except($request->all(), ['_token'])));
        return redirect()->back()->with('info', 'Заявка отправлена');
    }
}
