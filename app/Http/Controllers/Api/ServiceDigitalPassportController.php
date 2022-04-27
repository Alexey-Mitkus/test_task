<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use Carbon\Carbon;
use App\Models\Organization;
use App\Models\Passport;
use App\Models\PassportCategory;

class ServiceDigitalPassportController extends BaseController
{
    public function store(Request $request)
    {
        $user = $request->user('api');

        $passport = $user->passports()->create([
            'name' => $request->filled('title') ? $request->input('title') : 'Без названия',
            'organization_id' => ( $request->input('organization') <> 0 ? $request->input('organization') : null ),
            'raw_organization' => ( $request->input('organization') <> 0 ? Organization::where('id', $request->input('organization'))->first()->name : null ),
            'category_id' => PassportCategory::where('slug', $request->input('type'))->first()->id,
            'prerequisite' => $request->input('json.prerequisites'),
            'description' => $request->input('json.description'),
            'start_at' => Carbon::parse($request->input('json.startDate')),
            'end_at' => Carbon::parse($request->input('json.finishDate')),
            'is_complete' => $request->input('ready'),
            'step' => $request->input('json.step'),
            'status' => $request->input('json.status'),
        ]);

        return $this->sendResponse($passport->toArray());
    }

    public function update(Passport $passport, Request $request)
    {
        $user = $request->user('api');

        if( !$user->hasRole('admin') && $passport->owner->id !== $user->id )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        $passport->update([
            'name' => $request->filled('title') ? $request->input('title') : 'Без названия',
            'organization_id' => ( $request->input('organization') <> 0 ? ( is_int($request->input('organization')) ? $request->input('organization') : null ) : null ),
            'raw_organization' => ( $request->input('organization') <> 0 ? ( is_int($request->input('organization')) ? ( Organization::where('id', $request->input('organization'))->count() ? optional(Organization::where('id', $request->input('organization'))->first())->name : $request->input('organization') ) : ( Organization::where('id', $request->input('organization'))->count() ? optional(Organization::where('id', $request->input('organization'))->first())->name : $request->input('organization') ) )  : $request->input('organization') ),
            'category_id' => PassportCategory::where('slug', $request->input('type'))->first()->id,
            'prerequisite' => $request->input('json.prerequisites'),
            'description' => $request->input('json.description'),
            'start_at' => Carbon::parse($request->input('json.startDate')),
            'end_at' => Carbon::parse($request->input('json.finishDate')),
            'vision' => $request->filled('json.vision') ? $request->input('json.vision') : null,
            'is_complete' => $request->input('ready'),
            'step' => $request->input('json.step'),
            'status' => $request->input('json.status'),
        ]);

        if( $passport->directors->count() )
        {
            $passport->directors()->detach();
        }

        if( $request->filled('json.director') )
        {
            if( !empty($request->input('json.director')) )
            {
                if( is_array($request->input('json.director')) )
                {
                    foreach($request->input('json.director') as $key => $director):
                        if( !empty($director) )
                        {
                            $passport->directors()->attach($passport->owner->id, [
                                'name' => $director
                            ]);
                        }
                    endforeach;
                }else{
                    $passport->directors()->attach($passport->owner->id, [
                        'name' => $request->input('json.director')
                    ]);
                }
            }
        }

        if( $passport->teams->count() )
        {
            $passport->teams()->detach();
        }

        if( $request->filled('json.teamMates') )
        {
            if( !empty($request->input('json.teamMates')) )
            {
                foreach($request->input('json.teamMates') as $key => $member):
                    if( !empty($member) )
                    {
                        $passport->teams()->attach($passport->owner->id, [
                            'name' => $member
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->risks->count() )
        {
            $passport->risks()->detach();
        }

        if( $request->filled('json.risks') )
        {
            if( !empty($request->input('json.risks')) )
            {
                foreach($request->input('json.risks') as $key => $risk):
                    if( !empty($risk) )
                    {
                        $passport->risks()->attach($passport->owner->id, [
                            'name' => $risk
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->results->count() )
        {
            $passport->results()->detach();
        }

        if( $request->filled('json.results') )
        {
            if( !empty($request->input('json.results')) )
            {
                foreach($request->input('json.results') as $key => $result):
                    if( !empty($result) )
                    {
                        $passport->results()->attach($passport->owner->id, [
                            'name' => $result
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->metrics->count() )
        {
            $passport->metrics()->detach();
        }

        if( $request->filled('json.metrics') )
        {
            if( !empty($request->input('json.metrics')) )
            {
                foreach($request->input('json.metrics') as $key => $metric):
                    if( !empty($metric) )
                    {
                        $passport->metrics()->attach($passport->owner->id, [
                            'name' => $metric['metric'],
                            'value' => $metric['index'],
                            'before' => $metric['dateBefore'],
                            'after' => $metric['dateAfter'],
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->resources->count() )
        {
            $passport->resources()->detach();
        }

        if( $request->filled('json.resources') )
        {
            if( !empty($request->input('json.resources')) )
            {
                foreach($request->input('json.resources') as $key => $resource):
                    if( !empty($resource) )
                    {
                        $passport->resources()->attach($passport->owner->id, [
                            'name' => $resource['resource'],
                            'value' => $resource['index']
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->budgets->count() )
        {
            $passport->budgets()->detach();
        }

        if( $request->filled('json.budget') )
        {
            if( !empty($request->input('json.budget')) )
            {
                foreach($request->input('json.budget') as $key => $budget):
                    if( !empty($budget) )
                    {
                        $passport->budgets()->attach($passport->owner->id, [
                            'name' => $budget['expense'],
                            'value' => $budget['item']
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->plans->count() )
        {
            $passport->plans()->detach();
        }

        if( $request->filled('json.plan') )
        {
            if( !empty($request->input('json.plan')) )
            {
                foreach($request->input('json.plan') as $key => $plan):
                    if( !empty($plan) )
                    {
                        $passport->plans()->attach($passport->owner->id, [
                            'name' => $plan['planName'],
                            'description' => ( !empty($plan['description']) ? $plan['description'] : null ),
                            'date' => Carbon::parse($plan['date'])
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->interests->count() )
        {
            $passport->interests()->detach();
        }

        if( $request->filled('json.interestMan') )
        {
            if( !empty($request->input('json.interestMan')) )
            {
                foreach($request->input('json.interestMan') as $key => $member):
                    if( !empty($member['name']) )
                    {
                        $passport->interests()->attach($passport->owner->id, [
                            'name' => $member['name'],
                            'role' => $member['role'],
                        ]);
                    }
                endforeach;
            }
        }

        if( $passport->objectives->count() )
        {
            $passport->objectives()->detach();
        }

        if( $request->filled('json.objectives') )
        {
            if( !empty($request->input('json.objectives')) )
            {
                foreach($request->input('json.objectives') as $key => $objective):
                    if( !empty($objective) )
                    {
                        $passport->objectives()->attach($passport->owner->id, [
                            'name' => $objective
                        ]);
                    }
                endforeach;
            }
        }

        return $this->sendResponse($passport->toArray());
    }

    public function copy(Passport $passport, Request $request)
    {
        $user = $request->user('api');

        if( !$user->hasRole('admin') && $passport->owner->id !== $user->id )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }
        
        $NewPassport = $passport->replicate()->fill([
            'name' => 'Копия: ' . $passport->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        $NewPassport->save();

        if( $passport->directors->count() )
        {
            foreach($passport->directors as $key => $row):
                $NewPassport->directors()->attach($NewPassport->owner->id, [
                    'name' => $row->name
                ]);
            endforeach;
        }

        if( $passport->teams->count() )
        {
            foreach($passport->teams as $key => $row):
                $NewPassport->teams()->attach($NewPassport->owner->id, [
                    'name' => $row->name
                ]);
            endforeach;
        }

        if( $passport->interests->count() )
        {
            foreach($passport->interests as $key => $row):
                $NewPassport->interests()->attach($NewPassport->owner->id, [
                    'name' => $row->name,
                    'role' => $row->role,
                ]);
            endforeach;
        }

        if( $passport->objectives->count() )
        {
            foreach($passport->objectives as $key => $row):
                $NewPassport->objectives()->attach($NewPassport->owner->id, [
                    'name' => $row->name
                ]);
            endforeach;
        }

        if( $passport->results->count() )
        {
            foreach($passport->results as $key => $row):
                $NewPassport->results()->attach($NewPassport->owner->id, [
                    'name' => $row->name
                ]);
            endforeach;
        }

        if( $passport->metrics->count() )
        {
            foreach($passport->metrics as $key => $row):
                $NewPassport->metrics()->attach($NewPassport->owner->id, [
                    'name' => $row->name,
                    'value' => $row->value,
                    'before' => $row->before,
                    'after' => $row->after,
                ]);
            endforeach;
        }

        if( $passport->resources->count() )
        {
            foreach($passport->resources as $key => $row):
                $NewPassport->resources()->attach($NewPassport->owner->id, [
                    'name' => $row->name,
                    'value' => $row->value
                ]);
            endforeach;
        }

        if( $passport->budgets->count() )
        {
            foreach($passport->budgets as $key => $row):
                $NewPassport->budgets()->attach($NewPassport->owner->id, [
                    'name' => $row->name,
                    'value' => $row->value
                ]);
            endforeach;
        }

        if( $passport->risks->count() )
        {
            foreach($passport->risks as $key => $row):
                $NewPassport->risks()->attach($NewPassport->owner->id, [
                    'name' => $row->name
                ]);
            endforeach;
        }

        if( $passport->plans->count() )
        {
            foreach($passport->plans as $key => $row):
                $passport->plans()->attach($passport->owner->id, [
                    'name' => $row->name,
                    'description' => $row->description,
                    'date' => \Carbon\Carbon::parse($row->date)
                ]);
            endforeach;
        }

        return $this->sendResponse($NewPassport->toArray());
    }

    public function destroy(Passport $passport, Request $request)
    {
        $user = $request->user('api');

        if( !$user->hasRole('admin') && $passport->owner->id !== $user->id )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        if( $passport->directors->count() )
        {
            $passport->directors()->detach();
        }

        if( $passport->teams->count() )
        {
            $passport->teams()->detach();
        }

        if( $passport->risks->count() )
        {
            $passport->risks()->detach();
        }

        if( $passport->results->count() )
        {
            $passport->results()->detach();
        }

        if( $passport->metrics->count() )
        {
            $passport->metrics()->detach();
        }

        if( $passport->resources->count() )
        {
            $passport->resources()->detach();
        }

        if( $passport->budgets->count() )
        {
            $passport->budgets()->detach();
        }

        if( $passport->plans->count() )
        {
            $passport->plans()->detach();
        }

        if( $passport->interests->count() )
        {
            $passport->interests()->detach();
        }

        if( $passport->objectives->count() )
        {
            $passport->objectives()->detach();
        }

        $passport->delete();

        return $this->sendResponse([]);
    }
}
