@extends('layouts.lk')
@section('content')
    @if(!$user->email_verified_at)
        <div class="container mb-5">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">Подтверждение адреса электронной почты</div>

                        <div class="card-body">
                            <div class="white-box">
                                <b class="box-title">Вам на почту отправлено письмо с кодом подтверждения.</b>

                                @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    <p>{{ __('Новая ссылка для подтверждения аккаунта была отправлена на Вашу почту') }}</p>
                                </div>
                            @endif
                            <div>
                            {{ __('Пожалуйста, проверьте почту и нажмите на ссылку в письме для подтверждения.') }}
                            {{ __('Если вы не получили письмо') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button style="font-size: 20px" type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('нажмите, чтобы отправить еще одно') }}</button>.
                            </form>
                                <br>
                                    <no-email ></no-email>
                            </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

@if($user->email_verified_at)
        {{-- Онбординг --}}
        {{-- <components-users-onboarding :first-entry="'{{$user->first_entry}}'" :user-id="'{{$user->id}}'" /> --}}
    @if($user->first_entry == 0)
        {{-- <mooodal></mooodal> --}}
    @else

    @endif
    
    <div class="container">
        {{-- <x-development-notice /> --}}
        <div class="row justify-content-center">

            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            <div class="col-lg-9 col-md-12 pt-4 p-0">
                <div class="card mb-5">
                    
                    <div class="white-box p-0">
                        <div class="row justify-content-center">
                            <div class="row">
                                <div class="col-md-12">
                                    <avatar-select avatar="{{$user->avatar}}"></avatar-select>
                                    <span class="checkStatus">
                                        {{-- Статус участника --}}
                                        @if($user->hasRole('user'))
                                            <span class="markSign "><span class="mark_icon"></span></span>
                                            <span class="small">{{$user->roles->first()->title}}</span>
                                        @else
                                            <span class="questionSign">?</span>
                                            <sup class="small">{{$user->roles->first()->title}}</sup>
                                        @endif
                                    </span>
                                    <div class="mt-1 ratingMore d-none d-lg-block">
                                            <div class="rating">
                                                <span class="text"  style="position: relative;">Заполненность профиля: {{$user->rating}}%</span>
                                                <div style="width: {{$user->rating}}%" class="bar"></div>
                                            </div>
                                                <div style="display: flex">
                                                    @if (Route::currentRouteName() != 'editprofile')
                                                        <a href="/profile/edit/" class="grey">Редактировать профиль</a>
                                                    @endif
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                {{--  --}}
                                <div class="col-md-8 d-none d-lg-block">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                    
                                    <h4 class="box-title mb-0 text-left pl-4 mt-3">
                                        {{$user->fields()->where('slug', 'last_name')->first()->pivot->value}} {{ $user->fields()->where('slug', 'first_name')->first()->pivot->value }} {{ $user->fields()->where('slug', 'middle_name')->first()->pivot->value }}
                                    </h4>
                                    @if( !empty(optional($user->job->organization)->name) || !empty($user->job->raw_organization) )
                                    <div class="workingPlace pl-4 pr-3">{{ !empty(optional($user->job->organization)->name) ? optional($user->job->organization)->name : $user->job->raw_organization }}</div>
                                    @endif
                                        <hr />
                                        <div class="row bio m-0 pl-4 pr-3">
                                            <div class="col-md-12 p-0">
                                                <profile-info-slide>
                                                    <template v-slot:short>
                                                        @if( !empty(optional($user->managment->role)->name) ) 
                                                        <div class="row m-0">
                                                            <div class="col-md-6 p-0 bio_legend">Роль в проектной деятельности:</div>
                                                            <div class="col-md-6 p-0 ">{{ $user->managment->role->name }}</div>
                                                        </div>
                                                        @endif
                    
                                                        @if( $user->fields()->where('slug', 'telephone')->count() )
                    
                                                                <div class="row m-0">
                                                                    <div class="col-md-6 p-0 bio_legend">Мобильный телефон:</div>
                                                                    <div class="col-md-6 p-0 ">{{ $user->fields()->where('slug', 'telephone')->first()->pivot->value }}</div>
                                                                </div>
                                                        @endif
                    
                                                        @if( $user->fields()->where('slug', 'birth_date')->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">День рождения:</div>
                                                                <div class="col-md-6 p-0 ">{{Date::parse($user->fields()->where('slug', 'birth_date')->first()->pivot->value)->format('j F Y г.')}}</div>
                    
                                                            </div>
                                                        @endif
                    
                                                        @if( $user->fields()->where('slug', 'skype')->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Skype:</div>
                                                                <div class="col-md-6 p-0 ">{{ $user->fields()->where('slug', 'skype')->first()->pivot->value }}</div>
                    
                                                            </div>
                                                        @endif
                    
                                                        @if( $user->fields()->where('slug', 'facebook')->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Facebook:</div>
                                                                <div class="col-md-6 p-0 ">
                                                                    @if(strpos($user->fields()->where('slug', 'facebook')->first()->pivot->value, 'facebook.com/'))
                                                                        <a target="_blank" href="{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}">{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}</a>
                                                                    @else
                                                                        <a target="_blank" href="https://www.facebook.com/{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}">{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}</a>
                                                                    @endif
                                                                </div>
                    
                                                            </div>
                                                        @endif
                    
                                                        @if( $user->fields()->where('slug', 'vkontakte')->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Вконтакте:</div>
                    
                                                                <div class="col-md-6 p-0 ">
                                                                    @if(strpos($user->fields()->where('slug', 'vkontakte')->first()->pivot->value, 'vk.com/'))
                                                                        <a target="_blank" href="{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}">{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}</a>
                                                                    @else
                                                                        <a target="_blank" href="https://vk.com/{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}">{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}</a>
                                                                    @endif
                                                                </div>
                    
                                                            </div>
                                                        @endif
                    
                    
                    
                                                        @if( $user->fields()->where('slug', 'instagram')->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Instagram:</div>
                    
                                                                <div class="col-md-6 p-0 ">
                                                                    @if(strpos($user->fields()->where('slug', 'instagram')->first()->pivot->value, 'instagram.com/'))
                                                                        <a target="_blank" href="{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}">{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}</a>
                                                                    @else
                                                                        <a target="_blank" href="https://www.instagram.com/{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}">{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}</a>
                                                                    @endif
                                                                </div>
                    
                                                            </div>
                                                        @endif
                    
                                                        @if( $user->fields()->where('slug', 'website')->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Личный сайт:</div>
                                                                <div class="col-md-6 p-0 ">{{$user->fields()->where('slug', 'website')->first()->pivot->value}}</div>
                                                            </div>
                                                        @endif
                                                        <hr />
                    
                                                    </template>
                                                    <template v-slot:long>
                                                        @if( $user->educations->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Образование:</div>
                                                                <div class="col-md-6 p-0 ">
                                                                    @foreach($user->educations as $key => $education)
                                                                    {{ $education->type->name }}<br/>
                                                                        @switch($education->type->slug)
                                                                            @case('advanced')
                                                                                @if( !empty($education->course_name) )
                                                                                    @if( !empty($education->course_link) )
                                                                                        <a target="_blank" href="{{$education->course_link}}">{{$education->course_name}}</a>
                                                                                    @else
                                                                                        {{ $education->course_name }}
                                                                                    @endif
                                                                                @endif
                                                                                <wbr>
                                                                            @break
                                                                            @case('high')
                                                                            @case('middle-professional')
                                                                            @default
                                                                                @if( !empty($education->university) )
                                                                                {{ $education->university->name}}
                                                                                @else
                                                                                    {{ $education->raw_education }}
                                                                                @endif
                                                                            @break
                                                                        @endswitch
                                                                        
                                                                        @if( !empty($education->end_at) || !empty($education->course_organization) )
                                                                            @if( !empty($education->end_at) )
                                                                                ({{ \Carbon\Carbon::parse($education->end_at)->format('Y')}})
                                                                            @endif
                                                                            @if( !empty($education->end_at) && !empty($education->course_organization) ),@endif
                                                                            @if( !empty($education->course_organization) )
                                                                                {{ $education->course_organization }}
                                                                            @endif
                                                                        @endif
                                                                        
                                                                        @if( !empty($education->position) )
                                                                            {{ $education->position }}
                                                                        @endif                                
                                                                        <hr/>
                                                                    @endforeach
                                                                </div>
                                                    
                                                            </div>
                                                        @endif
                                                    
                                                        
                                                        @if( !empty(optional($user->job)->tags) )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Опыт:</div>
                                                                <div class="col-md-6 p-0 ">
                                                    
                                                                    @foreach($user->job->tags as $ex)
                                                                        {{$ex->pivot->value}}@if(!$loop->last),&nbsp;@endif
                                                                    @endforeach
                                                    
                                                                </div>
                                                    
                                                            </div>
                                                        @endif
                                                    
                                                        @if( !empty($user->managment->start_at) && !empty($user->managment->end_at) )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Стаж проектной деятельности:</div>
                                                                <div class="col-md-6 p-0 ">{{ageText( $user->managment->experience )}}</div>
                                                    
                                                            </div>
                                                        @endif
                                                    
                                                        @if( !empty(optional($user->managment)) && optional($user->managment)->methodologies->count() )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Используемые методологии:</div>
                                                                <div class="col-md-6 p-0 ">
                                                                    @foreach($user->managment->methodologies as $method)
                                                                        {{ $loop->index == $loop->count-1 ? $method->pivot->value : $method->pivot->value . ', ' }}
                                                                    @endforeach
                                                                </div>
                                                    
                                                            </div>
                                                    
                                                        @endif
                                                        
                                                        @if( !empty(optional($user->managment)->getting) )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Что вы хотите получить в проектном сообществе?</div>
                                                                <div class="col-md-6 p-0 ">{{ $user->managment->getting }}</div>
                                                    
                                                            </div>
                                                        @endif
                                                    
                                                        @if( !empty(optional($user->managment)->share) )
                                                            <div class="row m-0">
                                                                <div class="col-md-6 p-0 bio_legend">Чем вы готовы поделиться с проектным сообществом? </div>
                                                                <div class="col-md-6 p-0 ">{{ $user->managment->share }}</div>
                                                    
                                                            </div>
                                                        @endif
                                                    </template>
                                                </profile-info-slide>
                    
                                            </div>
                                        </div>
                                </div>
                                
                                <div class="col-md-8 d-md-block pl-4 d-lg-none p-0">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <h5 class="box-title mb-0">
                                        <span>{{$user->fields()->where('slug', 'last_name')->first()->pivot->value}} {{ $user->fields()->where('slug', 'first_name')->first()->pivot->value }} {{ $user->fields()->where('slug', 'middle_name')->first()->pivot->value }}</span>
                                    </h5>
                                    @if( !empty(optional($user->job->organization)->name) || !empty($user->job->raw_organization) )
                                    <div  class="workingPlace ">{{ !empty(optional($user->job->organization)->name) ? optional($user->job->organization)->name : $user->job->raw_organization }}</div>
                                    @endif
                                    <hr />
                                </div>
                            </div>
                    
                    {{-- Нужная мобильная версия --}}
                            <div class="row">
                                <div class="mt-1 ratingMore  d-lg-none">
                                                <div class="rating" data-toggle="tooltip">
                                                    <span class="text"  style="position: relative">Заполненность профиля: {{$user->rating}}%</span>
                    
                                                    <div style="width: {{$user->rating}}%" class="bar"></div>
                                                </div>
                                                @if($user->rating < 50)
                                                    <div class="lk_announcement mt-3 mb-3 pt-3 pb-3">
                                                        <span class="info_icon"></span>
                                                        <p class="text font-weight-bold">
                    
                                                            @if( !$user->avatar )
                                                                Загрузите свое фото и заполните профиль не менее, чем на 50%, чтобы получить доступ к профилям других участников сообщества. Это займет не более 5 минут.
                                                            @else
                                                                Заполните профиль не менее, чем на 50%, чтобы получить доступ к профилям других участников сообщества. Это займет не более 5 минут.
                                                            @endif
                                                        </p>
                                                    </div>
                                                @endif
                    
                                                <div >
                                                    @if (Route::currentRouteName() != 'editprofile')
                                                        <a href="/profile/edit/" class="grey">Редактировать профиль</a>
                                                    @endif
                                                </div>
                                        </div>
                                </div>
                            </div>
                    
                            {{-- Основная информация (Мобильная верстка)--}}
                                <div class="user-info d-lg-none">
                                    <profile-info-slide>
                                        <template v-slot:short>
                                            {{-- Роль в проектной детельности --}}
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/project.svg" style="width: 40px;" alt="role">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Роль в проектной деятельности:</div>
                                                        <div class="text-lk-bio p-0  bio_legend">{{ $user->managment->role->name }}</div>
                                                    </div>
                                                </div>
                    
                                            {{-- Мобильный телефон --}}
                                            @if( $user->fields()->where('slug', 'telephone')->exists() )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/phone-call.svg" style="width: 45px;" alt="phone" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Мобильный телефон:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">{{ $user->fields()->where('slug', 'telephone')->first()->pivot->value }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- День Рождения --}}
                                            @if( $user->fields()->where('slug', 'birth_date')->count() )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/dr.svg" style="width: 45px;" alt="calendar">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">День рождения:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">{{Date::parse($user->fields()->where('slug', 'birth_date')->first()->pivot->value)->format('j F Y г.')}}</div>
                                                    </div>
                                                </div>
                                            @endif
                    
                                            {{-- Skype --}}
                                            @if( $user->fields()->where('slug', 'skype')->count() )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/skype.svg" style="width: 35px;" alt="skype">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Skype:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">{{ $user->fields()->where('slug', 'skype')->first()->pivot->value }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- Facebook --}}
                                            @if( $user->fields()->where('slug', 'facebook')->count() )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/facebook.svg" style="width: 35px;" alt="facebook" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Facebook:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">
                                                            @if(strpos($user->fields()->where('slug', 'facebook')->first()->pivot->value, 'facebook.com/'))
                                                                <a target="_blank" href="{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}">{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}</a>
                                                            @else
                                                                <a target="_blank" href="https://www.facebook.com/{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}">{{$user->fields()->where('slug', 'facebook')->first()->pivot->value}}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                    
                                            {{-- В Контакте --}}
                                            @if( $user->fields()->where('slug', 'vkontakte')->count() )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/vk.svg" style="width: 35px;" alt="vkontakte">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Вконтакте:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">
                                                            @if(strpos($user->fields()->where('slug', 'vkontakte')->first()->pivot->value, 'vk.com/'))
                                                                <a target="_blank" href="{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}">{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}</a>
                                                            @else
                                                                <a target="_blank" href="https://vk.com/{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}">{{$user->fields()->where('slug', 'vkontakte')->first()->pivot->value}}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                    
                                            {{-- Instagram --}}
                                            @if($user->fields()->where('slug', 'instagram')->count())
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/inst.svg" style="width: 33px;" alt="instagram" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk-bio p-0 ">Instagram:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">
                                                            @if(strpos($user->fields()->where('slug', 'instagram')->first()->pivot->value, 'instagram.com/'))
                                                                <a target="_blank" href="{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}">{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}</a>
                                                            @else
                                                                <a target="_blank" href="https://www.instagram.com/{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}">{{$user->fields()->where('slug', 'instagram')->first()->pivot->value}}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                    
                                            {{-- Личный сайт --}}
                                            @if($user->fields()->where('slug', 'website')->count() )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/globe.svg" style="width: 34px;" alt="site">
                                                    <div class="user-info__bio">
                                                        <div class="text-lk-bio p-0">Личный сайт:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">{{$user->fields()->where('slug', 'website')->first()->pivot->value}}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            <hr />
                                        </template>
                                        <template v-slot:long>
                                            {{-- Образование --}}
                                            @if( !empty($user->educations) )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/mortarboard.svg" style="width: 40px; height: 100%;" alt="" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="col-md-11 p-0 text-lk-bio">Образование:</div>
                                        
                                                        <div class="col-md-12 p-0 text-lk">
                                                            @foreach($user->educations as $key => $education)
                                                            {{ $education->type->name }}<br/>
                                                                @switch($education->type->slug)
                                                                    @case('advanced')
                                                                        @if( !empty($education->course_name) )
                                                                            @if( !empty($education->course_link) )
                                                                                <a target="_blank" href="{{$education->course_link}}">{{$education->course_name}}</a>
                                                                            @else
                                                                                {{ $education->course_name }}
                                                                            @endif
                                                                        @endif
                                                                        <wbr>
                                                                    @break
                                                                    @case('high')
                                                                    @case('middle-professional')
                                                                    @default
                                                                        @if( !empty($education->university) )
                                                                        {{ $education->university->name}}
                                                                        @else
                                                                            {{ $education->raw_education }}
                                                                        @endif
                                                                    @break
                                                                @endswitch
                                                                
                                                                @if( !empty($education->end_at) || !empty($education->course_organization) )
                                                                    @if( !empty($education->end_at) )
                                                                        ({{ \Carbon\Carbon::parse($education->end_at)->format('Y')}})
                                                                    @endif
                                                                    @if( !empty($education->end_at) && !empty($education->course_organization) ),@endif
                                                                    @if( !empty($education->course_organization) )
                                                                        {{ $education->course_organization }}
                                                                    @endif
                                                                @endif
                                                                
                                                                @if( !empty($education->position) )
                                                                    ({{ $education->position }})
                                                                @endif                                
                                                                <hr/>
                                                            @endforeach
                                                            </div>
                                                    </div>
                                                </div>
                                            @endif
                                        
                                            {{-- Опыт  --}}
                                            @if( !empty(optional($user->job)->tags) )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/increase.svg" style="width: 45px;" alt="cap" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Опыт:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">
                                        
                                                        @foreach($user->job->tags as $ex)
                                                            {{$ex->pivot->value}}@if(!$loop->last),&nbsp;@endif
                                                        @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        
                                            {{-- Стаж проектной деятельности --}}
                                            @if( !empty($user->managment->start_at) && !empty($user->managment->end_at) )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/staj.svg" style="width: 45px;" alt="book" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Стаж проектной деятельности:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">{{ageText($user->managment->experience)}}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- Используемые методологии --}}
                                            @if( !empty(optional($user->managment)) && optional($user->managment)->methodologies->count() )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/metod.svg" style="width: 45px;" alt="case" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Используемые методологии:</div>
                                                        <div class="text-lk-bio p-0 bio_legend">
                                                            @foreach($user->managment->methodologies as $method)
                                                                {{ $loop->index == $loop->count-1 ? $method->pivot->value : $method->pivot->value . ', ' }}
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        
                                            {{-- Что вы хотите получить в проектном сообществе? --}}
                                            @if( !empty(optional($user->managment)->getting) )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/wish.svg" style="width: 45px;" alt="books" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Что вы хотите получить в проектном сообществе?</div>
                                                        <div class="text-lk-bio p-0 bio_legend">{{ $user->managment->getting }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- Чем вы готовы поделиться с проектным сообществом? --}}
                                            @if( !empty(optional($user->managment)->share) )
                                                <div class="user-info__item bio">
                                                    <div class="user-info__icon">
                                                        <img src="img/lk/del.svg" style="width: 45px;" alt="envelop" class="rounded">
                                                    </div>
                                                    <div class="user-info__bio">
                                                        <div class="text-lk p-0 ">Чем вы готовы поделиться с проектным сообществом? </div>
                                                        <div class="text-lk-bio p-0 bio_legend">{{ $user->managment->share }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        
                                        </template>
                                    </profile-info-slide>
                                </div>        
                                <div class="d-md-none d-lg-block">
                                    @if($user->rating < 50)
                                        <div class="lk_announcement mt-3 mb-3">
                                            <span class="info_icon"></span>
                                            <p class="text font-weight-bold">
                    
                                                @if(!$user->avatar)
                                                    Загрузите свое фото и заполните профиль не менее, чем на 50%, чтобы получить доступ к профилям других участников сообщества. Это займет не более 5 минут.
                                                @else
                                                    Заполните профиль не менее, чем на 50%, чтобы получить доступ к профилям других участников сообщества. Это займет не более 5 минут.
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
