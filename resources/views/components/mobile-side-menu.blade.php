<div class="header_mobile_menu">
    {{-- <div class="logo_button">
        <a href="/" class="logo_mob">
            <img style="width: 300px" src="/img/logo.svg" alt="">
        </a>
    </div> --}}
    <ul class="side_nav">
        <hr>
        <li class="{!! Request::routeIs('user.*') && !Request::routeIs('user.notification.*') ? 'active' : '' !!}">
            <a href="{{ route('user.index') }}"><span class="profile_icon"></span>Моя страница</a>
        </li>

        
        @if( Auth::check() )
        <hr>
        <li class="{!! Request::routeIs('user.notification.*') ? 'active' : '' !!}">
            <a href="{{ route('user.notification.index') }}"><span class="notifications_icon"></span>Уведомления
                @if( Auth::user()->unreadNotifications->count() )
                    <div class="notifications_count">{{ Auth::user()->unreadNotifications->count() }}</div>
                @endif
            </a>
        </li>
        @endif

        <hr>
        <li class="{!! Request::routeIs('news.*') ? 'active' : '' !!}">
            <a href="{{ route('news.index') }}"><span class="news_icon"></span>Новости</a>
        </li>
        
        @if(Auth::guest())
            <hr>
            <li>
                
                <a href="#" data-toggle="tooltip" data-placement="top" 
                    title="Для доступа к разделу необходимо заполнить профиль хотя бы на 50% и получить верификацию."><span class="participants_icon"></span>
                Участники</a>
            </li>
        {{-- @elseif((Auth::user()->hasRole('user') || Auth::user()->hasRole('admin')) && Auth::user()->rating('count') >= 50) --}}
        @elseif((Auth::user()->hasRole('user') || Auth::user()->hasRole('admin')) )
            <hr>
            <li class="{!! Request::routeIs('participant.*') ? 'active' : '' !!}">
                <a href="{{ route('participant.index') }}"><span class="participants_icon"></span>Участники</a>
            </li>
        @else
            <hr>
            <li >
                <a href="#" data-toggle="tooltip" data-placement="top" 
                    title="Для доступа к разделу необходимо заполнить профиль хотя бы на 50% и получить верификацию."><span class="friends_icon"></span>
                Участники</a>
            </li>
        @endif
    
        <hr>
        <li class="{!! Request::routeIs('speaker.*') ? 'active' : '' !!}">
            <a href="{{ route('speaker.index') }}"><span class="thoughts_icon"></span>Здравые мысли</a>
        </li>
    
        
        {{-- @if(Auth::check())
        <hr>
            @if ((Auth::user() && Auth::user()->projects && Auth::user()->projects->count() > 0) || (Auth::user()->id == 34 || Auth::user()->id == 177))
                <li class="@if (Route::currentRouteName() == 'passages') {{'active'}} @endif"><span class="personal_projects_icon"></span><a href="/passages">Мои проекты</a></li>
            @endif
        @endif --}}
        
        
        <hr>
        <li class="{!! Request::routeIs('user.document.*') ? 'active' : '' !!}">
            <a href="{{ route('user.document.index') }}"><span class="documents_icon"></span>Мои документы</a>
        </li>
        

        <hr>
        <li class="{!! Request::routeIs('community-project.*') ? 'active' : '' !!}">
            <a href="{{ route('community-project.index') }}"><span class="projects_icon"></span>Проекты сообщества</a>
        </li>
        
        
        <hr>
        <li class="{!! Request::routeIs('service.*') ? 'active' : '' !!}">
            <a href="{{ route('service.index') }}"><span class="services_icon"></span>Сервисы</a>
        </li>
        
        {{-- Робот Гоша, отображается в меню только на странице профиля --}}
        @if (Route::currentRouteName() == 'account')
            @if (Auth::guest())
            {{-- @elseif (Auth::user()->hasRole('admin') || Auth::user()->hasRole('user') || Auth::user()->hasRole('unverified')) --}}
            @else
                <hr>
                <li class="onboarding-show">
                    <span class="onboarding_icon"></span>
                    {{-- <img class="onboarding-show" src="{{asset('/storage/images/onboarding/onboarding-bot.png')}}" alt="onboarding" width="25" height="25"> --}}
                    <span class="onboarding-show">Робот Гоша</span>
                </li>
            @endif
        @endif 
        <hr>
        @if(Auth::guest())
            <li class="mt-5">
                <div class="hidden_menu_item ">
                    <a  href="/home"><b>Войти</b></a>
                </div>
            </li>
            <li class="">
                <div class="hidden_menu_item">
                    <a href="/register"><b>Регистрация</b></a>
                </div>
            </li>
            @else
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <span class="logout_icon"></span>
                    {{ __('Выйти из профиля') }}
                </a>
            </li>
        @endif
                

        @if(Auth::guest())
        @elseif(Auth::user()->hasRole('admin'))
        <hr />
        <li><a href="/admin/users"><span class="admin_icon"></span>Панель администратора</a></li>
        @endif
    </ul>



    <ul class="header_mobile_list" style="display: none">
            <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a  href="/">На главную</a></a>
                </div>
            </li>
        @if(Auth::guest())
            <li class="hidden_menu_mob">
                <div class="hidden_menu_item ">
                    <a  href="/home"><b>Войти</b></a>
                </div>
            </li>
            <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a href="/register"><b>Регистрация</b></a>
                </div>
            </li>
        @endif

        @if(Auth::user())
            <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a  href="/home">Моя страница</a>
                </div>
            </li>
            <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a  href="{{ route('user.notification.index') }}">Уведомления</a>
                </div>
            </li>
        @endif

        @if(Auth::guest())
        {{-- @elseif((Auth::user()->hasRole('user') || Auth::user()->hasRole('admin')) && Auth::user()->rating('count') > 80) --}}
        @elseif((Auth::user()->hasRole('user') || Auth::user()->hasRole('admin')) )
            <li class="hidden_menu_mob">
                <div class="hidden_menu_item" >
                    <a  href="{{ route('participant.index') }}">Участники</a>
                </div>
            </li>
        @endif
            <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a href="{{ route('speaker.index') }}">Здравые мысли</a>
                </div>
            </li>

            <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a href="/news">Новости</a>
                </div>
            </li>

            <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a href="{{ route('community-project.index') }}">Проекты</a>
                </div>
            </li>

            {{-- <li class="hidden_menu_mob">
                <div class="hidden_menu_item">
                    <a href="/pasport">Цифровой паспорт</a>
                </div>
            </li> --}}
            @if (Route::currentRouteName() == 'account')
            <li class="hidden_menu_mob onboarding-show">
                <div class="hidden_menu_item">
                    <img class="onboarding-show mb-2" src="{{asset('/storage/images/onboarding/onboarding-bot.png')}}" 
                        alt="" width="30" height="30">
                    <span class="onboarding-show onboarding-hover">Робот Гоша</span>
                </div>
            </li>
            @endif
        
        @if(Auth::user())
            <li  class="hidden_menu_mob">
                <div class="hidden_menu_item mb-3">
                    <a class="" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    {{ __('Выйти из профиля') }}
                </div>
                </a>
            </li>
        @endif
        

        @if(Auth::user() && Auth::user()->hasRole('admin'))
            <hr />
            <li class="hidden_menu_mob @if (Route::currentRouteName() == 'adminusers' || 'admin') {{'active'}} @endif">
                <div class="hidden_menu_item">
                    <a href="/admin/users">Пользователи</a>
                </div>
            </li>

            <li class="hidden_menu_mob @if (Route::currentRouteName() == 'adminnotifications') {{'active'}} @endif">
            <div class="hidden_menu_item">
            <a href="/admin/notifications">Уведомления</a>
            </div>
            </li>
            <li class="hidden_menu_mob @if (Route::currentRouteName() == 'adminupdates') {{'active'}} @endif">
                <div class="hidden_menu_item">
                <a href="/admin/updates">Обновления</a>
                </div>
            </li>
        
            <hr />
            <li>
                <div class="hidden_menu_item">
                <a href="/home">Выйти из АП</a>
                </div>
            </li>
        @endif
    </ul>
</div>
