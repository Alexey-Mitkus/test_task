<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Passport;

class ServiceDigitalIdeaController extends Controller
{
    public function __construct()
    {
        SEOMeta::setTitle(env('APP_DEFAULT_SEO_TITLE'));
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return redirect()->route('service.digital.passport.create');
    }

    public function create(Request $request)
    {
        SEOMeta::setTitle('Цифровая идея');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');
        
        $user = Auth::user();       

        return view('services.digitals.ideas.create', compact('user'));
    }

    public function edit(Passport $passport, request $request)
    {
        $user = Auth::user(); 
        
        $passport = Passport::with(['category', 'organization', 'owner', 'directors', 'teams', 'interests', 'objectives', 'results', 'metrics', 'resources', 'budgets', 'risks', 'plans'])->where('slug', $passport->slug)->first();

        if( $passport )
        {
            $interestMan = [];
            if( $passport->interests->count() )
            {
                foreach($passport->interests as $key => $interest):
                    $interestMan[] = [
                        'name' => $interest->pivot->name,
                        'role' => $interest->pivot->role
                    ];
                endforeach;
            }

            $resources = [];
            if( $passport->resources->count() )
            {
                foreach($passport->resources as $key => $resource):
                    $resources[] = [
                        'resource' => $resource->pivot->name,
                        'index' => $resource->pivot->value
                    ];
                endforeach;
            }

            $budgets = [];
            if( $passport->budgets->count() )
            {
                foreach($passport->budgets as $key => $budget):
                    $budgets[] = [
                        'expense' => $budget->pivot->name,
                        'item' => $budget->pivot->value
                    ];
                endforeach;
            }

            $plans = [];
            if( $passport->plans->count() )
            {
                foreach($passport->plans as $key => $plan):
                    $plans[] = [
                        'planName' => $plan->pivot->name,
                        'date' => Carbon::parse($plan->pivot->date)->format('Y-m-d')
                    ];
                endforeach;
            }

            $metrics = [];
            if( $passport->metrics->count() )
            {
                foreach($passport->metrics as $key => $metric):
                    $metrics[] = [
                        'metric' => $metric->pivot->name,
                        'index' => $metric->pivot->value,
                        'dateBefore' => $metric->pivot->before,
                        'dateAfter' => $metric->pivot->after
                    ];
                endforeach;
            }

            $passport = collect([
                'id' => $passport->id,
                'slug' => $passport->slug,
                'title' => $passport->name,
                'organization' => !empty($passport->organization) ? optional($passport->organization)->name : $passport->raw_organization,
                'raw_organization' => $passport->raw_organization,
                'organization_id' => optional($passport->organization)->id,
                'type' => optional($passport->category)->slug,
                'ready' => $passport->is_complete ? 1 : 0,
                'date' => Carbon::parse($passport->created_at)->format('d.m.Y'),
                'json' => [
                    'description' => $passport->description,
                    'prerequisites' => $passport->prerequisite,
                    'step' => $passport->step,
                    'startDate' => Carbon::parse($passport->start_at)->format('Y-m-d'),
                    'finishDate' => Carbon::parse($passport->end_at)->format('Y-m-d'),
                    'director' => optional(optional($passport->directors->first())->pivot)->name,
                    'status' => $passport->status,
                    'teamMates' => $passport->teams->count() ? $passport->teams->pluck('pivot.name')->toArray() : [],
                    'objectives' => $passport->objectives->count() ? $passport->objectives->pluck('pivot.name')->toArray() : [],
                    'results' => $passport->results->count() ? $passport->results->pluck('pivot.name')->toArray() : [],
                    'risks' => $passport->risks->count() ? $passport->risks->pluck('pivot.name')->toArray() : [],
                    'interestMan' => $interestMan,
                    'resources' => $resources,
                    'budget' => $budgets,
                    'plan' => $plans,
                    'metrics' => $metrics,
                    'vision' => $passport->vision ? $passport->vision : null,
                ]
            ]);
        }
        return view('services.digitals.ideas.edit', compact('passport', 'user'));
    }
}
