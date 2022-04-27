<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;
use App\Models\EventLog;
use App\Models\EventLogCategory;

class EventController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $events = EventLog::with(['category', 'target', 'handler'])->orderBy('id', 'ASC')->paginate(($request->has('limit') ? (int) $request->input('limit') : 25));

        SEOMeta::setTitle('Обновления');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        if( $request->has('page') && $request->input('page') > 1 )
        {
            SEOMeta::setTitle('Обновления - страница ' . $request->input('page') . ' из ' . $events->lastPage());
        }
        
        return view('dashboard.events.index', compact('user', 'events'));
    }
}
