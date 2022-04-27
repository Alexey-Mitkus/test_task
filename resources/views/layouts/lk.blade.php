<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @yield('meta')
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Проектное сообщество – Главная</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="Мы - сообщество лидеров изменений, развивающих здравоохранение города Москвы"/>
    <meta name="keywords" content="Проектное сообщество"/>
    <meta name="author" content="Департамент здравоохранения города Москвы"/>
    <meta name="publisher-email" content="{{ env('MAIL_ADMINISTRATOR_EMAIL') }}"/>
    <meta name="robots" content="index, follow"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if( Auth::check() )
    <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endif
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    @if(Auth::check())
        @php echo "<script> var user_id = ". Auth::id() . ";</script>"; @endphp
    @else 
        @php echo "<script> var user_id = 0;</script>"; @endphp
    @endif
    @if( !App::isLocal() )
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172140922-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-172140922-1', {
            'user_id': user_id
        });
    </script>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (m, e, t, r, i, k, a) {
            m[i] = m[i] || function () {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(57693100, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true,
            userParams: {
                vip_status: true,
                child: 1,
                child_age: 1,
                UserID: user_id
            }
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/57693100" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    @endif
    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">
    <link rel="stylesheet" href="{{ mix('css/categories.css')}}"/>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}"/>
    @if( !App::isLocal() )
    {{-- <script src="//code-ya.jivosite.com/widget/PZK1oBZGM6" async></script> --}}
    @endif
</head>
<body>

<div id="app">
    <div class="d-lg-none">
        <div class="close_bg"></div>
        @include('components.mobile-side-menu')
    </div>

    <div class="wrapper">
        @include('components.header')
        <main class="brownbg">
            <div class="container p-0">
                <div class="nobg mb-5">
                    @yield('content')

                </div>
            </div>
        </main>
        <footer id="contacts">
            <div class="container">
                <h3>Контакты</h3>
                <div class="contacts">
                    <a href="https://wa.me/79951200508">Написать в WhatsApp</a>
                </div>
                <div class="contacts">
                    <span>Почта:</span>
                    <a href="mailto:{{ env('MAIL_ADMINISTRATOR_EMAIL') }}">{{ env('MAIL_ADMINISTRATOR_EMAIL') }}</a>
                </div>
            </div>
        </footer>
    </div>
</div>

<script type="text/javascript" src="{{ asset("/js/jquery.3.4.1.js") }}"></script>
{{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js defer"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous" defer>
</script> --}}
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" 
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" 
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" 
    crossorigin="anonymous"></script>

<script src="{{ asset('js/swiper.min.js') }}" defer></script>
<script src="{{ asset('js/main.js') }}" defer></script>

</body>
</html>