<ul class="side_nav">

    {{-- МОЯ СТРАНИЦА --}}
    <li class="{!! Request::routeIs('user.*') && !Request::routeIs('user.notification.*') && !Request::routeIs('user.document.*') ? 'active' : '' !!}">
        <a href="{{ route('user.index') }}">
            <span class="profile_icon"></span> 
            <span class="text">Моя страница</span>    
        </a>
    </li>

    {{-- УВЕДОМЛЕНИЯ --}}
    @if( !empty($user) )

        <li class="{!! Request::routeIs('user.notification.*') ? 'active' : '' !!}">
            <a href="{{ route('user.notification.index') }}">
                <span class="notifications_icon"></span> 
                <span class="text">Уведомления</span>
                @if( $notification->unreadNotifications->count() )
                    <div class="notifications_count">{{ $notification->unreadNotifications->count() }}</div>
                @endif
            </a>
        </li>

    @endif

    {{-- НОВОСТИ --}}
    <li class="{!! Request::routeIs('news.*') ? 'active' : '' !!}">
        <a href="{{ route('news.index') }}">
            <span class="news_icon"></span> 
            <span class="text">Новости</span>    
        </a>
    </li>

    {{-- УЧАСТНИКИ --}}
    @if( empty($user) )

        <li>
            <a href="#">
                <div class="side-menu__tooltip"><span></span>Для доступа к разделу необходимо зерегестрироваться и получить верификацию</div>
                <span class="participants_icon"></span>
                <span class="text">Участники</span> 
            </a>
        </li>

    @else

        @if ( $user->hasRole('admin') || $user->can('verified') || $user->rating >= 50 )

            <li class="{!! Request::routeIs('participant.*') ? 'active' : '' !!}">
                <a href="{{ route('participant.index') }}">
                    <span class="participants_icon"></span>
                    <span class="text">Участники</span> 
                </a>
            </li>

        @else

            <li>
                <a href="#">
                    <div class="side-menu__tooltip"><span></span>Для доступа к разделу необходимо получить верификацию</div>
                    <span class="participants_icon"></span>
                    <span class="text">Участники</span> 
                </a>
            </li>

        @endif

    @endif

    {{-- ЗДРАВЫЕ МЫСЛИ --}}
    <li class="{!! Request::routeIs('speaker.*') ? 'active' : '' !!}">
        <a href="{{ route('speaker.index') }}">
            <span class="thoughts_icon"></span>
            <span class="text">Здравые мысли</span>  
        </a>
    </li>

    {{-- МОЙ ДОКУМЕНТЫ --}}

    <li class="{!! Request::routeIs('user.document.*') || Request::routeIs('service.digital.*') ? 'active' : '' !!}">
        <a href="{{ route('user.document.index') }}">
            <span class="documents_icon"></span>
            <span class="text">Мои документы</span>   
        </a>
    </li>

    {{-- ПРОЕКТЫ СООБЩЕСТВА --}}

    <li class="{!! Request::routeIs('community-project.*') ? 'active' : '' !!}">
        <a href="{{ route('community-project.index') }}">
            <span class="projects_icon"></span>
            <span class="text">Проекты сообщества</span>
        </a>
    </li>

    {{-- СЕРВИСЫ--}}

    <li class="{!! Request::routeIs('service.*') && !Request::routeIs('service.digital.*') ? 'active' : '' !!}">
        <a href="{{ route('service.index') }}">
            <span class="services_icon"></span>
            <span class="text">Сервисы</span>
        </a>
    </li>

    <li class="{!! Request::routeIs('knowledge-base.*') ? 'active' : '' !!}">
        <a href="{{ route('knowledge-base.index') }}">
            <span class="database_icon"></span>
            <span class="text">База знаний</span>
        </a>
    </li>
    
    {{-- ОНБОРДИНГ (ОТОБРАЖАЕТЯ ТОЛЬКО НА МОЕЙ СТРАНИЦЕ)) --}}

    @if( Request::routeIs('user.*') && !Request::routeIs('user.notification.*') && !Request::routeIs('user.document.*') )

        <li class="onboarding-show">
            <div class="unready">
                <span class="onboarding_icon"></span>
                <span class="text">Робот Гоша</span>
            </div>
        </li>

    @endif
</ul>

{{-- ПАНЕЛЬ АДМИНИСТРАТОРА --}}

@if( !empty($user) && $user->hasRole('admin') )

<hr>
<ul class="side_nav">
    <li>
        <a href="{{ route('dashboard.index') }}">
            <span class="admin_icon"></span>
            <span class="text">Панель администратора</span>
        </a>
    </li>
</ul>

@endif