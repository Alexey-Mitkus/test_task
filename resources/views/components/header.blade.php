<header class="position-relative">
    <div class="container p-0">

        {{-- <button class="open-mob-nav"></button> --}}
        {{-- <div class="overlay"></div> --}}
        <div class="wrap-mob-nav">
            {{-- <button class="close-mob-nav"></button> --}}
            {{-- <a href="/" class="logo">
                <img src="/img/logo.svg" alt="">
            </a> --}}
            <nav class="d-flex justify-content-between">
                <a href="/">
                    <img class="logo-project desktop" src="/images/header_small_logo.svg" alt="Проектное сообщество">
                </a>
                <ul class="menu">
                        {{-- <li class="d-none d-lg-block align-self-center mr-2">
                            <a class="nav-link" href="/">На главную</a>
                        </li> --}}

                @guest
                    <li class="nav-item align-self-center d-none d-lg-block">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                    </li>

                    @if (Route::has('register'))
                        <li class="nav-item align-self-center d-none d-lg-block">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                        </li>
                    @endif
                @else
                    <li class="d-none d-lg-block align-self-center">
                        
                        <a class="nav-link pr-0" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Выйти из профиля') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
                {{-- Бургер мобильного меню --}}
                    <li class="d-flex justify-content-center d-lg-none">
                        <div id="menu-burger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
