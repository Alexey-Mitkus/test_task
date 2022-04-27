<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrationToNewDatabaseCommand extends Command
{
    protected $signature = 'database:migrateToNew';
    protected $description = 'migration old database to new script';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////   NEW
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        $UsersFields = \App\Models\UserField::all();
		$users = \Illuminate\Support\Facades\DB::connection('mysql_old')->table('users')->get();
		$fieldsTypes = \Illuminate\Support\Facades\DB::connection('mysql_old')->table('fields')->get();
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        /*
        if( !empty($users) )
        {
            foreach($users as $oldUser)
            {
                $user = \App\Models\User::create([
                    'id' => $oldUser->id,
                    'email' => $oldUser->email,
                    'api_token' => \Illuminate\Support\Str::random(60),
                    'password' => $oldUser->password,
                    'referal' => $oldUser->ref_name,
                    'remember_token' => $oldUser->remember_token,
                    'created_at' => \Carbon\Carbon::parse($oldUser->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::parse($oldUser->updated_at)->format('Y-m-d H:i:s'),
                ]);

                if( !empty($oldUser->email_verified_at) )
                {
                    $user->markEmailAsVerified();
                }

                if( !empty($oldUser->name) )
                {
                    $field = $UsersFields->where('slug', 'first_name')->first();
                    $user->fields()->attach($field->id, [
                        'value' => $oldUser->name,
                        'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                        'is_show' => 0
                    ]);
                }

                $userRoles = \Illuminate\Support\Facades\DB::connection('mysql_old')->table('role_user')->where('user_id', $oldUser->id)->get();
                if( !empty($userRoles) )
                {
                    foreach ($userRoles as $key => $roleRow)
                    {
                        switch ($roleRow->role_id)
                        {
                            case 1:
                                // admin
                                $user->assignRole(\Spatie\Permission\Models\Role::where('name', 'admin')->get());
                            break;
                            case 2:
                                // user
                                $user->givePermissionTo(\Spatie\Permission\Models\Permission::where('name', 'verified')->get());
                            break;
                            case 3:
                            break;
                            default:
                            break;
                        }
                    }
                }

                $users_fields = \Illuminate\Support\Facades\DB::connection('mysql_old')->table('field_user')->where('user_id', $oldUser->id)->get();
                if( !empty($users_fields) )
                {
                    foreach($users_fields as $fkey => $userOldFields)
                    {
                        switch($userOldFields->field_id)
                        {
                            case 1:
                                //Ваше отчество
                                $field = $UsersFields->where('slug', 'middle_name')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 2:
                                //Ваша фамилия
                                $field = $UsersFields->where('slug', 'last_name')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 3:
                                //Ваше место работы
                                if( is_string($userOldFields->value) )
                                {
                                    $userOldFields->value = json_decode($userOldFields->value);
                                }

                                if( empty($user->job) )
                                {
                                    $job = $user->job()->create([
                                        'organization_id' => \App\Models\Organization::where('name', $userOldFields->value->wo_place)->orWhere('abbreviation', $userOldFields->value->wo_place)->count() ? \App\Models\Organization::where('name', $userOldFields->value->wo_place)->orWhere('abbreviation', $userOldFields->value->wo_place)->first()->id : null,
                                        'raw_organization' => $userOldFields->value->wo_place,
                                        'position' => $userOldFields->value->wo_position,
                                        'start_at' => !empty($userOldFields->value->wo_start) ? \Carbon\Carbon::now()->setYear($userOldFields->value->wo_start)->startOfYear()->format('Y-m-d H:i:s') : null,
                                        'end_at' => null
                                    ]);

                                    if( !empty($userOldFields->value->wo_experience) )
                                    {
                                        foreach($userOldFields->value->wo_experience as $element):
                                            $job->tags()->attach($job->id, [
                                                'user_id' => $user->id,
                                                'value' => $element
                                            ]);
                                        endforeach;
                                    }
                                }else{
                                    $job = $user->job()->update([
                                        'organization_id' => \App\Models\Organization::where('name', $userOldFields->value->wo_place)->orWhere('abbreviation', $userOldFields->value->wo_place)->count() ? \App\Models\Organization::where('name', $userOldFields->value->wo_place)->orWhere('abbreviation', $userOldFields->value->wo_place)->first()->id : null,
                                        'raw_organization' => $userOldFields->value->wo_place,
                                        'position' => $userOldFields->value->wo_position,
                                        'start_at' => !empty($userOldFields->value->wo_start) ? \Carbon\Carbon::now()->setYear($userOldFields->value->wo_start)->startOfYear()->format('Y-m-d H:i:s') : null,
                                        'end_at' => null
                                    ]);
                                    
                                    if( $job->tags->count() )
                                    {
                                        $job->tags()->detach();
                                    }

                                    if( !empty($userOldFields->value->wo_experience) )
                                    {
                                        foreach($userOldFields->value->wo_experience as $element):
                                            $job->tags()->attach($job->id, [
                                                'user_id' => $user->id,
                                                'value' => $element
                                            ]);
                                        endforeach;
                                    }
                                }
                                
                                    // {
                                    //     "wo_place":"Департамент здравоохранения города Москвы",
                                    //     "wo_start":"2019",
                                    //     "wo_end":null,
                                    //     "wo_speciality":null,
                                    //     "wo_position":"Эксперт сектора методологии проектной деятельности",
                                    //     "wo_experience":[
                                    //         "Аналитика",
                                    //         "Управление проектом",
                                    //         "Организация мероприятий",
                                    //         "Коммуникации",
                                    //         "Маркетинг",
                                    //         "Управление продуктом",
                                    //         "Исследования",
                                    //         "Excel",
                                    //         "Связи с общественностью",
                                    //         "Digital"
                                    //     ],
                                    //     "step":4
                                    // }
                                
                                
                            break;
                            case 4:
                                //Ваша должность (не отображается в профиле)
                            break;
                            case 5:
                                //Роль в проектной деятельности (при наличии)
                                $user->managment()->updateOrCreate(
                                    ['user_id' => $user->id],
                                    [
                                        'role_id' => \App\Models\UserManagmentRole::where('name', $userOldFields->value)->count() ? \App\Models\UserManagmentRole::where('name', $userOldFields->value)->first()->id : null
                                    ]
                                );
                            break;
                            case 6:
                                //Ваш мобильный телефон для связи
                                $field = $UsersFields->where('slug', 'telephone')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 7:
                                //День рождения
                                $field = $UsersFields->where('slug', 'birth_date')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 8:
                                //Skype
                                $field = $UsersFields->where('slug', 'skype')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 9:
                                //Facebook
                                $field = $UsersFields->where('slug', 'facebook')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 10:
                                //Вконтакте
                                $field = $UsersFields->where('slug', 'vkontakte')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 11:
                                //Instagram
                                $field = $UsersFields->where('slug', 'instagram')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 12:
                                //Личный сайт
                                $field = $UsersFields->where('slug', 'website')->first();
                                $user->fields()->attach($field->id, [
                                    'value' => $userOldFields->value,
                                    'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                                    'is_show' => 0
                                ]);
                            break;
                            case 14:
                                //Образование
                                if( is_string($userOldFields->value) )
                                {
                                    $userOldFields->value = json_decode($userOldFields->value);
                                }

                                $user->educations()->create([
                                    'education_category_id' => !empty($userOldFields->value->ed_type) ? ( \App\Models\EducationCategory::where('name', $userOldFields->value->ed_type)->count() ? \App\Models\EducationCategory::where('name', $userOldFields->value->ed_type)->first()->id : null ) : null,
                                    'education_id' => !empty($userOldFields->value->ed_universityTitle) ? ( \App\Models\Education::where('name', $userOldFields->value->ed_universityTitle)->count() ? \App\Models\Education::where('name', $userOldFields->value->ed_universityTitle)->first()->id : null ) : null,
                                    'raw_education' => !empty($userOldFields->value->ed_universityTitle) ? $userOldFields->value->ed_universityTitle : null,
                                    'position' => !empty($userOldFields->value->ed_speciality) ? $userOldFields->value->ed_speciality : null,
                                    'course_name' => !empty($userOldFields->value->ed_courseName) ? $userOldFields->value->ed_courseName : null,
                                    'course_organization' => !empty($userOldFields->value->ed_courseHead) ? $userOldFields->value->ed_courseHead : null,
                                    'course_link' => !empty($userOldFields->value->ed_courseUrl) ? $userOldFields->value->ed_courseUrl : null,
                                    'end_at' => !empty($userOldFields->value->ed_end) ? \Carbon\Carbon::now()->setYear( (int) $userOldFields->value->ed_end)->startOfYear()->format('Y-m-d H:i:s') : null,
                                ]);


                                
                                    // {
                                    //     "bio_id":359,
                                    //     "ed_type":"Курсы / Повышение квалификации",
                                    //     "ed_country":null,
                                    //     "ed_city":null,
                                    //     "ed_university":null,
                                    //     "ed_universityTitle":null,
                                    //     "ed_faculty":null,
                                    //     "ed_status":null,
                                    //     "ed_end":"2020",
                                    //     "ed_speciality":null,
                                    //     "ed_courseName":"Профессия: Аналитик",
                                    //     "ed_courseHead":"Product Star",
                                    //     "ed_courseUrl":"https://productstar.ru/analytics",
                                    //     "step":3
                                    // },
                                    // {
                                    //     "bio_id":null,
                                    //     "ed_type":"Курсы / Повышение квалификации",
                                    //     "ed_country":"RU",
                                    //     "ed_city":null,
                                    //     "ed_university":null,
                                    //     "ed_universityTitle":null,
                                    //     "ed_faculty":null,
                                    //     "ed_status":null,
                                    //     "ed_end":null,
                                    //     "ed_speciality":null,
                                    //     "ed_courseName":"Python",
                                    //     "ed_courseHead":"Stepik",
                                    //     "ed_courseUrl":null,
                                    //     "step":3
                                    // },
                                    // {
                                    //     "bio_id":366,
                                    //     "ed_type":"Высшее",
                                    //     "ed_country":"RU",
                                    //     "ed_city":1,
                                    //     "ed_university":263,
                                    //     "ed_universityTitle":"РУТ (МИИТ) (бывш. МГУПС Императора Николая II)",
                                    //     "ed_faculty":4328,
                                    //     "ed_status":"Очное",
                                    //     "ed_end":null,
                                    //     "ed_speciality":"Реклама и связи с общественностью с фере государственного и муниципального управления",
                                    //     "ed_courseName":null,
                                    //     "ed_courseHead":null,
                                    //     "ed_courseUrl":null,
                                    //     "step":3
                                    // }
                                

                            break;
                            case 16:
                                //Стаж проектной деятельности
                                $user->managment()->updateOrCreate(
                                    ['user_id' => $user->id],
                                    [
                                        'start_at' => \Carbon\Carbon::now()->startOfYear()->subYear( (int) $userOldFields->value)->format('Y-m-d H:i:s'),
                                        'end_at' => \Carbon\Carbon::now()->startOfYear()->format('Y-m-d H:i:s')
                                    ]
                                );
                            break;
                            case 17:
                                //Используемые методологии
                                if( is_string($userOldFields->value) )
                                {
                                    $userOldFields->value = json_decode($userOldFields->value);
                                }

                                if( is_array($userOldFields->value) && !empty($userOldFields->value) )
                                {
                                    if( empty($user->managment) )
                                    {
                                        $user->managment()->updateOrCreate(
                                            ['user_id' => $user->id],
                                            []
                                        );
                                    }
                                    foreach ($userOldFields->value as $key => $tags)
                                    {
                                        $user->managment->methodologies()->attach($user->managment->id, [
                                            'user_id' => $user->id,
                                            'value' => $tags
                                        ]);
                                    }
                                }
                                
                                    // [
                                    //     "Scrum",
                                    //     "Agile",
                                    //     "Классический проектный менеджмент",
                                    //     "Lean",
                                    //     "Kanban",
                                    //     "Waterfall"
                                    // ]
                                
                            break;
                            case 18:
                                //Что вы хотите получить в проектном сообществе?
                                if( empty($user->managment) )
                                {
                                    $user->managment()->create([
                                        'getting' => $userOldFields->value
                                    ]);
                                }else{
                                    $user->managment->update([
                                        'getting' => $userOldFields->value
                                    ]);
                                }
                            break;
                            case 19:
                                //Чем вы готовы поделиться с проектным сообществом?
                                if( empty($user->managment) )
                                {
                                    $user->managment()->create([
                                        'share' => $userOldFields->value
                                    ]);
                                }else{
                                    $user->managment->update([
                                        'share' => $userOldFields->value
                                    ]);
                                }
                            break;
                            case 20:
                                //Опыт
                            break;
                            default:
                            break;
                        }

                    }
                }

                ///////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////   База Знаний
                ///////////////////////////////////////////////////////////////////////////////////////////////////


                ///////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////   Passports
                ///////////////////////////////////////////////////////////////////////////////////////////////////
                $passports = \Illuminate\Support\Facades\DB::connection('mysql_old')->table('passports')->where('user_id', $oldUser->id)->get();
                if( $passports->count() )
                {
                    foreach($passports as $key => $passport):
                        
                        if( is_string($passport->json) )
                        {
                            $passport->json = json_decode($passport->json);
                        }
        
                        $NewPassport = $user->passports()->create([
                            'name' => $passport->title,
                            'organization_id' => \App\Models\Organization::where('name', $passport->organization)->orWhere('abbreviation', $passport->organization)->count() ? \App\Models\Organization::where('name', $passport->organization)->orWhere('abbreviation', $passport->organization)->first()->id : null,
                            'raw_organization' => $passport->organization,
                            'category_id' => \App\Models\PassportCategory::where('slug', $passport->type)->first()->id,
                            'prerequisite' => !empty($passport->json->prerequisites) ? $passport->json->prerequisites : null,
                            'description' => !empty($passport->json->description) ? $passport->json->description : null,
                            'vision' => !empty($passport->json->vision) ? $passport->json->vision : null,
                            'start_at' => !empty($passport->json->startDate) ? \Carbon\Carbon::parse($passport->json->startDate) : null,
                            'end_at' => !empty($passport->json->finishDate) ? \Carbon\Carbon::parse($passport->json->finishDate) : null,
                            'is_complete' => !empty($passport->ready) ? $passport->ready : 0,
                            'step' => !empty($passport->json->step) ? $passport->json->step : null,
                            'status' => !empty($passport->json->status) ? $passport->json->status : null,
                        ]);

                        if( $NewPassport->objectives->count() )
                        {
                            $NewPassport->objectives()->detach();
                        }

                        if( !empty($passport->objectives) )
                        {
                            foreach($passport->objectives as $key => $objective):
                                if( !empty($objective) )
                                {
                                    $NewPassport->objectives()->attach($NewPassport->owner->id, [
                                        'name' => $objective
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->interests->count() )
                        {
                            $NewPassport->interests()->detach();
                        }

                        if( !empty($passport->interestMan) )
                        {
                            foreach($passport->interestMan as $key => $member):
                                if( !empty($member['name']) )
                                {
                                    $NewPassport->interests()->attach($NewPassport->owner->id, [
                                        'name' => $member['name'],
                                        'role' => $member['role'],
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->plans->count() )
                        {
                            $NewPassport->plans()->detach();
                        }

                        if( !empty($passport->plan) )
                        {
                            foreach($passport->plan as $key => $plan):
                                if( !empty($plan) )
                                {
                                    $NewPassport->plans()->attach($NewPassport->owner->id, [
                                        'name' => $plan['planName'],
                                        'description' => ( !empty($plan['description']) ? $plan['description'] : null ),
                                        'date' => \Carbon\Carbon::parse($plan['date'])
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->budgets->count() )
                        {
                            $NewPassport->budgets()->detach();
                        }
                
                        if( !empty($passport->budget) )
                        {
                            foreach($passport->budget as $key => $budget):
                                if( !empty($budget) )
                                {
                                    $NewPassport->budgets()->attach($NewPassport->owner->id, [
                                        'name' => $budget['expense'],
                                        'value' => $budget['item']
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->resources->count() )
                        {
                            $NewPassport->resources()->detach();
                        }
                
                        if( !empty($passport->resources) )
                        {
                            foreach($passport->resources as $key => $resource):
                                if( !empty($resource) )
                                {
                                    $NewPassport->resources()->attach($NewPassport->owner->id, [
                                        'name' => $resource['resource'],
                                        'value' => $resource['index']
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->metrics->count() )
                        {
                            $NewPassport->metrics()->detach();
                        }
                        
                        if( !empty($passport->metrics) )
                        {
                            foreach($passport->metrics as $key => $metric):
                                if( !empty($metric) )
                                {
                                    $NewPassport->metrics()->attach($NewPassport->owner->id, [
                                        'name' => $metric['metric'],
                                        'value' => $metric['index'],
                                        'before' => $metric['dateBefore'],
                                        'after' => $metric['dateAfter'],
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->results->count() )
                        {
                            $NewPassport->results()->detach();
                        }

                        if( !empty($passport->results) )
                        {
                            foreach($passport->results as $key => $result):
                                if( !empty($result) )
                                {
                                    $NewPassport->results()->attach($NewPassport->owner->id, [
                                        'name' => $result
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->risks->count() )
                        {
                            $NewPassport->risks()->detach();
                        }

                        if( !empty($passport->risks) )
                        {
                            foreach($passport->risks as $key => $risk):
                                if( !empty($risk) )
                                {
                                    $NewPassport->risks()->attach($NewPassport->owner->id, [
                                        'name' => $risk
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->teams->count() )
                        {
                            $NewPassport->teams()->detach();
                        }
                
                        if( !empty($passport->teamMates) )
                        {
                            foreach($passport->teamMates as $key => $member):
                                if( !empty($member) )
                                {
                                    $NewPassport->teams()->attach($NewPassport->owner->id, [
                                        'name' => $member
                                    ]);
                                }
                            endforeach;
                        }

                        if( $NewPassport->directors->count() )
                        {
                            $NewPassport->directors()->detach();
                        }

                        if( !empty($passport->director) )
                        {
                            if( is_array($passport->director) )
                            {
                                foreach($passport->director as $key => $director):
                                    if( !empty($director) )
                                    {
                                        $NewPassport->directors()->attach($NewPassport->owner->id, [
                                            'name' => $director
                                        ]);
                                    }
                                endforeach;
                            }else{
                                $NewPassport->directors()->attach($NewPassport->owner->id, [
                                    'name' => $passport->director
                                ]);
                            }
                        }
                    endforeach;
                }
            }
        }
        */

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////   База Знаний
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        /*
        $formats = \App\KbFormat::whereNull('parent_id')->get();
        if( !empty($formats) )
        {
            foreach($formats as $key => $frm):
                $format = \App\Models\KnowledgeBase\KbFormat::create([
                    'name' => $frm->name,
                    'user_id' => \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->id,
                    'is_active' => $frm->is_active,
                    'slug' => !empty($frm->slug) ? $frm->slug : null
                ]);

                if( !empty($frm->childrens) )
                {
                    $this->KbchildrensFormats($format, $frm->childrens);
                }

            endforeach;
        }

        $themes = \App\KbTheme::whereNull('parent_id')->get();
        if( !empty($themes) )
        {
            foreach($themes as $key => $th):
                
                $theme = \App\Models\KnowledgeBase\KbTheme::create([
                    'name' => $th->name,
                    'parent_id' => null,
                    'user_id' => \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->id,
                    'is_active' => $th->is_active,
                    'slug' => !empty($th->slug) ? $th->slug : null,
                ]);

                if( !empty($th->childrens) )
                {
                    $this->KbchildrensThemes($theme, $th->childrens);
                }

            endforeach;
        }

        $posts = \App\KbPost::get();
        if( !empty($posts) )
        {
            foreach($posts as $key => $tp):
                $post = \App\Models\KnowledgeBase\KbPost::create([
                    'name' => $tp->name,
                    'description' => $tp->description,
                    'lang' => $tp->lang,
                    'status_id' => $tp->status_id,
                    'user_id' => \App\Models\User::withTrashed()->where('email', $tp->owner->email)->first()->id,
                    'views' => $tp->views,
                    'is_active' => $tp->is_active,
                    'slug' => !empty($tp->slug) ? $tp->slug : null,
                ]);

                if( $tp->themes->count() )
                {
                    foreach ($tp->themes as $fkey => $ftheme)
                    {
                        $post->themes()->attach(\App\Models\KnowledgeBase\KbTheme::where('slug', $ftheme->slug)->first()->id);
                    }
                }

                if( $tp->formats->count() )
                {
                    foreach ($tp->formats as $fkey => $fformat)
                    {
                        $post->formats()->attach(\App\Models\KnowledgeBase\KbFormat::where('slug', $fformat->slug)->first()->id);
                    }
                }

                if( $tp->tags->count() )
                {
                    foreach ($tp->tags as $fkey => $ftag)
                    {
                        $tag = \App\Models\KnowledgeBase\KbTag::firstOrCreate([
                            'name' => $ftag->name
                        ],[
                            'user_id' => $post->owner->id
                        ]);

                        if( !empty($tag) )
                        {
                            $post->tags()->attach($tag->id);
                        }
                    }
                }

                $medias = \App\Media::where([
                    ['mediatable_type', 'App\Models\KnowledgeBase\KbPost'],
                    ['mediatable_id', $tp->id],
                ])->get();

                if( $medias->count() )
                {
                    foreach($medias as $key => $mmedia):
                        switch ($mmedia->type)
                        {
                            case 'image':
                                $post->ImageRepositoryDelete($collection = 'poster');
                                $post->ImageRepository($files = \Illuminate\Support\Facades\Storage::disk($mmedia->disk)->path($mmedia->folder . $mmedia->src), $sizesArray = config('knowledgebase.posters.sizes'), $storageDisk = 'public', $folderStorageDisk = '/uploads/knowledgebase/', $Author = $post->owner->id);
                            break;
                            default:
                                $post->media()->create([
                                    'name' => $mmedia->name,
                                    'type' => $mmedia->type,
                                    'size' => $mmedia->size,
                                    'extension' => $mmedia->extension,
                                    'width' => $mmedia->width,
                                    'height' => $mmedia->height,
                                    'user_id' => $post->owner->id,
                                    'src' => $mmedia->src,
                                    'mimes' => $mmedia->mimes,
                                    'disk' => $mmedia->disk,
                                    'folder' => $mmedia->folder,
                                    'storage' => $mmedia->storage,
                                    'storage_data' => $mmedia->storage_data,
                                    'slug' => $mmedia->slug
                                ]);
                            break;
                        }
                    endforeach;
                }
            endforeach;
        }
        */

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////   До объновление полей с ролью в проектной деятельности
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        if( !empty($users) )
        {
            foreach($users as $oldUser)
            {
                $users_fields = \Illuminate\Support\Facades\DB::connection('mysql_old')->table('field_user')->where('user_id', $oldUser->id)->get();
                $user = \App\Models\User::where('email', $oldUser->email)->first();

                if( !empty($users_fields) )
                {
                    foreach($users_fields as $fkey => $userOldFields)
                    {
                        switch($userOldFields->field_id)
                        {
                            case 5:
                                //Роль в проектной деятельности (при наличии)
                                $user->managment()->updateOrCreate(
                                    ['user_id' => $user->id],
                                    [
                                        'role_id' => \App\Models\UserManagmentRole::where('name', $userOldFields->value)->count() ? \App\Models\UserManagmentRole::where('name', $userOldFields->value)->first()->id : null
                                    ]
                                );
                            break;
                        }
                    }
                }
            }
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////   Загрузка изображений юзеров
        ///////////////////////////////////////////////////////////////////////////////////////////////////
        if( !empty($users) )
        {
            foreach($users as $oldUser)
            {
                $user = \App\Models\User::where('email', $oldUser->email)->first();

                if( \Illuminate\Support\Facades\Storage::disk('local')->exists('public/' . $oldUser->avatar) )
                {
                    $user->ImageRepositoryDelete($collection = null);
                    try {
                        $user->ImageRepository($files = \Illuminate\Support\Facades\Storage::disk('local')->path('public/' . $oldUser->avatar), $sizesArray = config('users.userpic.sizes'), $storageDisk = 'public', $folderStorageDisk = '/uploads/users/', $Author = $user->id);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    \Illuminate\Support\Facades\Storage::disk('local')->delete('public/' . $oldUser->avatar);
                }
            }
        }

        return 0;
    }

    public function KbchildrensFormats($parentFormat, $childrens)
    {
        if( empty($parentFormat) )
        {
            return null;
        }

        if( empty($childrens) )
        {
            return null;
        }

        if( !empty($childrens) )
        {
            foreach($childrens as $key => $childformat):
                $format = $parentFormat->childrens()->create([
                    'name' => $childformat->name,
                    'user_id' => \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->id,
                    'is_active' => $childformat->is_active,
                    'slug' => !empty($frm->slug) ? $frm->slug : null
                ]);

                if( !empty($childformat->childrens) )
                {
                    $this->KbchildrensFormats($format, $childformat->childrens);
                }

            endforeach;
        }

        return null;

    }

    public function KbchildrensThemes($parentTheme, $childrens)
    {
        if( empty($parentTheme) )
        {
            return null;
        }

        if( empty($childrens) )
        {
            return null;
        }

        if( !empty($childrens) )
        {
            foreach($childrens as $key => $childtheme):
                $theme = $parentTheme->childrens()->create([
                    'name' => $childtheme->name,
                    'user_id' => \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->id,
                    'is_active' => $childtheme->is_active,
                    'slug' => !empty($childtheme->slug) ? $childtheme->slug : null,
                ]);

                if( !empty($childtheme->childrens) )
                {
                    $this->KbchildrensThemes($theme, $childtheme->childrens);
                }

            endforeach;
        }

        return null;

    }
}
