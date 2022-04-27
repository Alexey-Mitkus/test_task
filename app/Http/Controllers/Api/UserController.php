<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;
use Carbon\Carbon;

use App\Models\Media;
use App\Models\User;
use App\Models\UserField;
use App\Models\Organization;
use App\Models\UserManagmentRole;
use App\Models\EducationCategory;
use App\Models\Education;
use App\Models\UserEducation;


class UserController extends BaseController
{

    protected $Intervention;

    public function __construct()
    {
        $this->Intervention = new ImageManager([
        	'driver' => 'gd'
        ]);
    }

    public function index(Request $request)
    {
        $users = User::search($request->get('search'))->orderBy('id', 'ASC')->get()->map(function($u){
            return $u->withFields([
                'first_name',
                'last_name',
                'middle_name',
                'full_name',
                'full_name_short'
            ]);
        });
        return $this->sendResponse($users->toArray());
    }

    public function auth(Request $request)
    {
        $user = $request->user('api');

        if( $request->filled('type') )
        {
            switch ($request->input('type'))
            {
                case 'fullData':
                    $user = User::with(['job', 'job.organization', 'managment', 'managment.role', 'educations', 'educations.university', 'educations.type', 'roles', 'permissions'])->where('id', $user->id)->first()->withFields()->setAppends(['avatar', 'rating']);
                break;
                default:
                break;
            }
        }
        return $this->sendResponse($user->toArray());
    }

    public function getFields(Request $request)
    {
        $user = $request->user('api');

        $fields = UserField::active()->get();

        return $this->sendResponse($fields->toArray());
    }

    public function show(User $user, Request $request)
    {
        if( $request->filled('type') )
        {
            switch ($request->input('type'))
            {
                case 'fullData':
                    $user = User::with(['job', 'job.organization', 'managment', 'managment.role', 'educations', 'educations.university', 'educations.type', 'roles', 'permissions'])->where('id', $user->id)->first()->withFields()->setAppends(['avatar', 'rating']);
                break;
                default:
                break;
            }
        }

        return $this->sendResponse($user->toArray());
    }

    public function passports(User $user, Request $request)
    {
        $passportsArray = collect([]);
        $passports = $user->passports()->with(['category', 'organization', 'owner', 'directors', 'teams', 'interests', 'objectives', 'results', 'metrics', 'resources', 'budgets', 'risks', 'plans'])->get();

        if( $passports->count() )
        {
            foreach($passports as $key => $passport)
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

                $passportsArray->push([
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
        }
        return $this->sendResponse($passportsArray->toArray());
    }

    public function notifications(User $user, Request $request)
    {
        $notifications = $user->notifications->toArray();
        $notifications = collect($notifications)->map(function($notification){
            if( !empty($notification['data']['sender']) )
            {
                $notification['data']['sender'] = User::withTrashed()->with(['job', 'job.organization', 'managment', 'managment.role', 'educations', 'educations.university', 'educations.type', 'roles', 'permissions'])->where('id', $notification['data']['sender']['id'])->first()->withFields([
                    'first_name',
                    'last_name',
                    'middle_name',
                    'full_name',
                    'full_name_short'
                ]);
            }
            return $notification;
        });

        return $this->sendResponse($notifications->toArray());
    }

    public function update(User $user, Request $request)
    {
        $AuthUser = $request->user('api');
        
        if( !$AuthUser->hasRole('admin') && $user->id !== $AuthUser->id )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        if( !$request->filled('type') )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        Cache::forget('users_fields_' . $user->id);

        switch ($request->input('type'))
        {
            case 'Photo':
                if( $request->hasFile('data') && $request->file('data')->isValid() )
                {
                    $user->ImageRepositoryDelete($collection = null);
                    $user->ImageRepository($files = $request->file('data'), $sizesArray = config('users.userpic.sizes'), $storageDisk = 'public', $folderStorageDisk = '/uploads/users/', $Author = $AuthUser->id);
                }
            break;
            case 'EducationInfo':

                if( $request->filled('data.slug') )
                {
                    $education = $user->educations()->where('slug', $request->input('data.slug'))->first();
                    $education->update([
                        'education_category_id' => $request->filled('data.type.slug') ? ( EducationCategory::where('slug', $request->input('data.type.slug'))->count() ? EducationCategory::where('slug', $request->input('data.type.slug'))->first()->id : null ) : null,
                        'education_id' => $request->filled('data.institution.id') ? $request->input('data.institution.id') : null,
                        'raw_education' => $request->filled('data.institution.name') ? $request->input('data.institution.name') : null,
                        'position' => $request->filled('data.speciality') ? $request->input('data.speciality') : null,
                        'course_name' => $request->filled('data.course_name') ? $request->input('data.course_name') : null,
                        'course_organization' => $request->filled('data.course_organization') ? $request->input('data.course_organization') : null,
                        'course_link' => $request->filled('data.course_link') ? $request->input('data.course_link') : null,
                        'end_at' => $request->filled('data.finishDate') ? Carbon::now()->setYear( (int) $request->input('data.finishDate'))->startOfYear()->format('Y-m-d H:i:s') : null,
                    ]);

                }else{

                    $user->educations()->create([
                        'education_category_id' => $request->filled('data.type.slug') ? ( EducationCategory::where('slug', $request->input('data.type.slug'))->count() ? EducationCategory::where('slug', $request->input('data.type.slug'))->first()->id : null ) : null,
                        'education_id' => $request->filled('data.institution.id') ? $request->input('data.institution.id') : null,
                        'raw_education' => $request->filled('data.institution.name') ? $request->input('data.institution.name') : null,
                        'position' => $request->filled('data.speciality') ? $request->input('data.speciality') : null,
                        'course_name' => $request->filled('data.course_name') ? $request->input('data.course_name') : null,
                        'course_organization' => $request->filled('data.course_organization') ? $request->input('data.course_organization') : null,
                        'course_link' => $request->filled('data.course_link') ? $request->input('data.course_link') : null,
                        'end_at' => $request->filled('data.finishDate') ? Carbon::now()->setYear( (int) $request->input('data.finishDate'))->startOfYear()->format('Y-m-d H:i:s') : null,
                    ]);

                }
            break;
            case 'ProjectInfo':
                $managment = $user->managment()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'role_id' => UserManagmentRole::where('name', $request->input('data.role'))->count() ? UserManagmentRole::where('name', $request->input('data.role'))->first()->id : null,
                        'start_at' => Carbon::now()->startOfYear()->subYear( (int) $request->input('data.experience'))->format('Y-m-d H:i:s'),
                        'end_at' => Carbon::now()->startOfYear()->format('Y-m-d H:i:s'),
                        'getting' => $request->input('data.get'),
                        'share' => $request->input('data.share'),
                    ]
                );
                
                if( $user->managment->methodologies->count() )
                {
                    $user->managment->methodologies()->detach();
                }

                if( !empty($request->input('data.selectedTags')) )
                {
                    foreach($request->input('data.selectedTags') as $element):
                        $user->managment->methodologies()->attach($user->managment->id, [
                            'user_id' => $user->id,
                            'value' => $element
                        ]);
                    endforeach;
                }

            break;
            case 'JobInfo':

                $job = $user->job()->updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    [
                        'organization_id' => Organization::where('name', $request->input('data.workplace.text'))->orWhere('abbreviation', $request->input('data.workplace.text'))->count() ? Organization::where('name', $request->input('data.workplace.text'))->orWhere('abbreviation', $request->input('data.workplace.text'))->first()->id : null,
                        'raw_organization' => $request->input('data.workplace.text'),
                        'position' => $request->input('data.position'),
                        'start_at' => !empty($request->input('data.jobStart')) ? Carbon::now()->setYear($request->input('data.jobStart'))->startOfYear()->format('Y-m-d H:i:s') : null,
                        'end_at' => null
                    ]
                );
                
                if( $job->tags->count() )
                {
                    $job->tags()->detach();
                }

                if( !empty($request->input('data.tags')) )
                {
                    foreach($request->input('data.tags') as $element):
                        $job->tags()->attach($job->id, [
                            'user_id' => $user->id,
                            'value' => $element
                        ]);
                    endforeach;
                }

            break;
            case 'ContactInfo':
                $UsersFields = UserField::all();
                if( $request->filled('data') && is_array($request->input('data')) )
                {
                    foreach($request->input('data') as $key => $element)
                    {
                        $field = $UsersFields->where('slug', $key)->first();

                        if( $user->fields()->where('slug', $key)->count() )
                        {
                            $user->fields()->detach($field->id);
                        }

                        if( !empty($element['value']) )
                        {
                            $user->fields()->attach($field->id, [
                                'value' => $element['value'],
                                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                'is_show' => $element['is_show'] == 1 ? 1 : 0
                            ]);
                        }
                    }
                }
            break;
            case 'totalInfo':
                $UsersFields = UserField::all();

                $field = $UsersFields->where('slug', 'first_name')->first();

                if( $user->fields()->where('slug', 'first_name')->count() )
                {
                    $user->fields()->detach($field->id);
                }

                if( !empty($request->input('data.first_name')) )
                {
                    $user->fields()->attach($field->id, [
                        'value' => $request->input('data.first_name'),
                        'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                        'is_show' => 0
                    ]);
                }

                $field = $UsersFields->where('slug', 'last_name')->first();

                if( $user->fields()->where('slug', 'last_name')->count() )
                {
                    $user->fields()->detach($field->id);
                }

                if( !empty($request->input('data.last_name')) )
                {
                    $user->fields()->attach($field->id, [
                        'value' => $request->input('data.last_name'),
                        'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                        'is_show' => 0
                    ]);
                }

                $field = $UsersFields->where('slug', 'middle_name')->first();

                if( $user->fields()->where('slug', 'middle_name')->count() )
                {
                    $user->fields()->detach($field->id);
                }

                if( !empty($request->input('data.middle_name')) )
                {
                    $user->fields()->attach($field->id, [
                        'value' => $request->input('data.middle_name'),
                        'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                        'is_show' => 0
                    ]);
                }

                $field = $UsersFields->where('slug', 'birth_date')->first();

                if( $user->fields()->where('slug', 'birth_date')->count() )
                {
                    $user->fields()->detach($field->id);
                }

                if( !empty($request->input('data.birth_date')) )
                {
                    $user->fields()->attach($field->id, [
                        'value' => \Carbon\Carbon::parse($request->input('data.birth_date'))->format('Y-m-d'),
                        'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                        'is_show' => $request->input('data.hideDate') == false || $request->input('data.hideDate') == 'false' ? 1 : 0
                    ]);
                }

            break;
            default:
                return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
            break;
        }

        if( $user->rating >= 25 && !$user->notifications()->where('type', 'App\Notifications\UserVerifyByAdminNotification')->orWhere('type', 'App\\Notifications\\UserVerifyByAdminNotification')->count() )
        {
            if( $user->hasPermissionTo(\Spatie\Permission\Models\Permission::where('name', 'verified')->first()->id) || $user->hasRole('admin') )
            {
                $user->notify(new \App\Notifications\UserVerifyByAdminNotification($user, $AuthUser));
            }
        }elseif ($user->rating < 25 && !$user->notifications()->where('type', 'App\Notifications\UserNoFillProfileNoVerifyNotification')->orWhere('type', 'App\\Notifications\\UserNoFillProfileNoVerifyNotification')->count() ) {

            if( !$user->hasPermissionTo(\Spatie\Permission\Models\Permission::where('name', 'verified')->first()->id) )
            {
                $user->notify(new \App\Notifications\UserNoFillProfileNoVerifyNotification($user, $AuthUser));
            }
        }

        if( $user->rating >= 25 && !$user->hasPermissionTo(\Spatie\Permission\Models\Permission::where('name', 'verified')->first()->id) )
        {
            User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->notify(new \App\Notifications\AdminFillUserProfileNotification($user));
        }

        return $this->sendResponse($user);
    }

    public function EducationDestroy(User $user, $education, Request $request)
    {
        $AuthUser = $request->user('api');

        if( !$AuthUser->hasRole('admin') && $user->id !== $AuthUser->id )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        $education = $user->educations()->where('slug', $education)->first();
        
        if( $education )
        {
            $education->delete();
        }

        return $this->sendResponse([]);
    }

    public function PushToFillProfile(User $user, Request $request)
    {
        $AuthUser = $request->user('api');

        if( !$AuthUser->hasRole('admin') )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        $user->notify(new \App\Notifications\UserToFillingProfileNotification($user, $AuthUser));

        return $this->sendResponse([]);
    }

    public function verify(User $user, Request $request)
    {
        $AuthUser = $request->user('api');

        if( !$AuthUser->hasRole('admin') )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        $user->givePermissionTo(\Spatie\Permission\Models\Permission::where('name', 'verified')->get());

        event(new \App\Events\VerifyUserByAdminEvent($user, $AuthUser));

        return $this->sendResponse([]);
    }

    public function destroy(User $user, Request $request)
    {
        $AuthUser = $request->user('api');

        if( !$AuthUser->hasRole('admin') )
        {
            return $this->sendError('Don\'t have permission', $errorMessages = [], $code = 200);
        }

        $user->delete();

        return $this->sendResponse([]);
    }
}
