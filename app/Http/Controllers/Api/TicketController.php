<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use Carbon\Carbon;
use App\Models\User;

class TicketController extends BaseController
{
    public function index(Request $request)
    {
        dd('index', $request->all());
    }

    public function store(Request $request)
    {
        $request->merge([
            'date' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        if( $request->filled('userID') )
        {
            $user = User::find($request->input('userID'));
            $request->merge([
                'user' => ( !empty($user) ? $user :  null )
            ]);
        }

        if( $request->filled('phone') )
        {
            $request->merge([
                'telephone' => $request->input('phone')
            ]);
        }
        
        // \Notification::route('mail', env('MAIL_ADMINISTRATOR_EMAIL'))->notify(new \App\Notifications\NewUserTicketNotification(Arr::except($request->all(), ['_token', 'userID', 'phone'])));
        \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->notify(new \App\Notifications\NewUserTicketNotification(Arr::except($request->all(), ['_token', 'userID', 'phone'])));

        return $this->sendResponse([]);
    }
}
