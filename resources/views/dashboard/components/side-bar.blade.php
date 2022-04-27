<ul class="side_nav">
    <li class="{!! Request::routeIs('dashboard.user.*') ? 'active' : '' !!}">
        <a href="{{ route('dashboard.user.index') }}">
            <span class="friends_icon"></span>
            <span class="text">Пользователи</span> 
        </a>
    </li>

    <li class="{!! Request::routeIs('dashboard.notification.*') ? 'active' : '' !!}">
    	<a href="{{ route('dashboard.notification.index') }}">
            <span class="notifications_icon"></span>
            <span class="text">Уведомления</span>
            @if( !empty($notification) && $notification->unreadNotifications->count() )
                <div class="notifications_count">{{ $notification->unreadNotifications->count() }}</div>
            @endif
        </a>
    </li>

    <li class="{!! Request::routeIs('dashboard.event.*') ? 'active' : '' !!}">
    	<a href="{{ route('dashboard.event.index') }}">
            <span class="updates_icon"></span>
            <span class="text">Обновления</span> 
        </a>
    </li>

    <li class="{!! Request::routeIs('dashboard.knowledge-base.*') ? 'active' : '' !!}">
        <a href="{{ route('dashboard.knowledge-base.index') }}">
            <span class="database_icon"></span>
            <span class="text">База знаний</span>
        </a>
    </li>

    {{-- <li class="hidden_menu">
        <span class="analysis_icon"></span>
        <span class="text">Аналитика*</span>
    </li>

    <li class="hidden_menu">
        <span class="databasecloud_icon"></span>
        <span class="text">Базы данных*</span>
    </li>
    <li class="hidden_menu">
        <span class="files_icon"></span>
        <span class="text">Файлы*</span>
    </li>

    <li class="hidden_menu">
        <span class="news_icon"></span>
        <span class="text">Новости*</span>
    </li>
    
    <li class="hidden_menu">
        <span class="database_icon"></span>
        <span class="text">База знаний*</span>
    </li>

    <li class="hidden_menu">
        <span class="projects_icon"></span>
        <span class="text">Проекты*</span>
    </li>

    <li class="hidden_menu">
        <span class="settings_icon"></span>
        <span class="text">Настройки*</span>
    </li>

    <li class="hidden_menu"><span class="small">* Данные разделы находятся в разработке</small></li> --}}
</ul>
<hr />
<ul class="side_nav">
    <li>
        <a href="{{ route('user.index') }}">
            <span class="admin_icon"></span>
            <span class="text">Выйти из АП</span>
        </a>
    </li>
</ul>