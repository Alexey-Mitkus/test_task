<!DOCTYPE html>
<html lang="ru">
<head>

    <meta property="og:url"                content="{{route('home')}}" />
    <meta property="og:title"              content="Проектное сообщество" />
    <meta property="og:image"              content="{{ asset('/img/logo_1.png') }}" />

    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Проектное сообщество – Главная</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="Мы - сообщество лидеров изменений, развивающих здравоохранение города Москвы" />
	<meta name="keywords" content="Проектное сообщество" />
	<meta name="author" content="Департамент здравоохранения города Москвы" />
	<meta name="publisher-email" content="{{ env('MAIL_ADMINISTRATOR_EMAIL') }}" />
	<meta name="robots" content="index, follow" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
	<script src="{{ asset('js/app.js') }}" defer></script>
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stylenew2.css') }}">
    @if( !App::isLocal() )
    <script src="//code-ya.jivosite.com/widget/PZK1oBZGM6" async></script>
    @endif
    <script>var user_id = {{ Auth::check() ? Auth::user()->id : 0 }};</script>
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
    <noscript><div><img src="https://mc.yandex.ru/watch/57693100" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    @endif
</head>
<body>
<div class="wrapper">
    <header>
        <div class="container" id="app">
            <button class="open-mob-nav"></button>
            <div class="overlay"></div>
            <div class="wrap-mob-nav">
                <button class="close-mob-nav"></button>
                <a href="#" class="logo">
                    <img src="img/logo.svg" alt="">
                </a>
                <nav>
                    <ul class="menu">

                        <li><a href="{{ route('news.index') }}">НОВОСТИ</a></li>
                        <li><a href="{{ route('community-project.index') }}">ПРОЕКТЫ</a></li>
                        <li> <a href="{{ route('speaker.index') }}">ЗДРАВЫЕ МЫСЛИ</a></li>
                        <li><a href="{{ route('service.index') }}">СЕРВИСЫ</a></li>
                        {{-- <li><a href="{{ route('knowledge-base.index') }}">БАЗА ЗНАНИЙ</a></li> --}}
                        
                        @auth
                        <li> <a href="{{ route('participant.index') }}">УЧАСТНИКИ</a></li>

                        <div class="d-flex">
                            <li><a class="btn btn_uppercase mr-3" href="{{ route('user.index') }}">Мой профиль</a></li>

                            <li><a class="btn" style="background:#9a0000;" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">ВЫЙТИ</a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
						@else
						<div class="d-flex">
						<li><a href="#" data-toggle="modal" data-target="#requestModal" class="btn btn_uppercase mr-3">Вход</a></li>
						@if (Route::has('register'))
                        <li><a style="background:#9a0000;" href="{{ route('register') }}" class="btn btn_uppercase">Регистрация</a></li>
                        </div>
                        @endif

						@endauth
            </div>

                    <div class="authAndAutorization">
                        @auth

                        <div class="d-flex p-3 flex-row-reverse">

                        <li><a class="btn" href="{{ route('user.index') }}">Мой профиль</a></li>
                        </div>
                        @else
                        <div class="d-flex p-3 flex-row-reverse">
                        <li><a href="#" data-toggle="modal" data-target="#requestModal" class="btn ml-3">Вход</a></li>
                        @if (Route::has('register'))
                        <li><a style="background:#9a0000;" href="{{ route('register') }}" class="btn">Регистрация</a></li>
                        </div>
                        @endif


                        @endauth
                     </div>
    </header>
    <main>
	    <div class="container">

        <div class="top">
            <div class="container container-main">
                <div class="logo">
                    <img src="img/logo.svg" alt="">
                </div>
                <span class="title">Создаем историю здравоохранения вместе</span>

                <div class="buttons justify-content-start">


					{{-- <div class="btn_fixed d-flex">
                        <a href="https://www.facebook.com/groups/community.zdrav"  class="btn big">Присоединиться к сообществу</a><a
                            href="https://www.facebook.com/groups/community.zdrav"><img  src="/img/faceb.svg"></a>
                    </div> --}}
                    <div class="btn_fixed d-flex">
                    	<a target="_blank" href="https://chat.whatsapp.com/Km3aG28E5Qw8QJl65oIpS9" class="btn big">Подписаться на уведомления</a>
                        <a href="https://chat.whatsapp.com/Km3aG28E5Qw8QJl65oIpS9"> <img src="/img/whats.svg"></a>
                    </div>

                    <div class="btn_fixed d-flex">
                        <a href="{{ route('speaker.index') }}" style="background:#9a0000;" class="btn big">Стать спикером</a>
                        <a href="{{ route('speaker.index') }}"><img src="/img/spiker.svg"></a>
                   </div>

                </div>
                <div class="site-navigation">
                    <ul class="main-navigation">
                        <li><a class="slowscroll" href="#">О сообществе</a></li>
                        <li><a class="slowscroll" href="#mission">Миссия</a></li>
                        <li><a class="slowscroll" href="#values">Ценности</a></li>
                        <li><a class="slowscroll" href="#benefits">Преимущества</a></li>
                        <li><a class="slowscroll" href="#contacts">Контакты</a></li> 
                    </ul>
                </div>

                <div class="about" id="about">
                    <span class="about__title pb-5">Мы - сообщество лидеров изменений, развивающих здравоохранение города Москвы</span>
                    <div class="about__text">
                        <p class="pt-3">Для этого используем инструменты
                            проектного управления, непрерывно
                            обучаемся и готовы делиться опытом с
                            новыми участниками.
                        </p>
                    </div>
                </div>
                <div id="mission" class="mission">
                    <h3>Наша миссия</h3>
                    <div class="mission__inner">
                        <div class="mission__img">
                            <img src="img/bg2.svg" alt="">
                        </div>
                        <div class="mission__text">
	                        <p>
		                        Объединять людей, ориентированных на развитие московского здравоохранения посредством <b>проектного управления</b>.
	                        </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
        <div id="values" class="values">
            <div class="container">
                <h3>Наши ценности</h3>
                <div class="values__inner">
                    <div class="values__item">
                        <div class="values__img">
                            <img src="img/1/a1.svg" alt="">
                        </div>
                        <span class="values__text">Сотрудничество</span>
                    </div>
                    <div class="values__item">
                        <div class="values__img">
                            <img src="img/1/a2.svg" alt="">
                        </div>
                        <span class="values__text">Непрерывное развитие</span>
                    </div>
                    <div class="values__item">
                        <div class="values__img pr-3">
                            <img src="img/vovle.svg" style="width: 130px" alt="">
                        </div>
                        <span class="values__text">Вовлечённость</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="benefits" id="benefits">
            <div class="container">
	            <br/><br/>
                <h3>Преимущества для участников</h3>
                <span class="benefits__title">Участие в сообществе бесплатно, но требует вовлеченности и самоотдачи</span>
                <div class="benefits__inner">
                    <div class="benefits__item benefits__item_red">
                        <span class="benefits__subtitle">Самореализация</span>
                        <p>Многие из нас достигли
                            определенного
                            профессионального уровня,
                            но чувствуют, что могут
                            добиться гораздо большего.
                            Могут стать инициаторами
                            тех изменений, которые
                            сделают жизнь других людей
                            хоть немного лучше. Мы
                            помогаем таким людям и
                            даем им для этого все
                            необходимые инструменты.</p>
                    </div>
                    <div class="benefits__item">
                        <span class="benefits__subtitle">Обучение на практике</span>
                        <p>В обучении проектному
                            управлению мы используем
                            метод active learning
                            (обучение на практике). Он
                            подразумевает минимум
                            теории и максимум кейсов,
                            при решении которых
                            участники изучают
                            различные методологии
                            проектного управления.
                        </p>
                    </div>
                    <div class="benefits__item">
                        <span class="benefits__subtitle">Профессиональная помощь сообщества и организаторов</span>
                        <p>В сообществе нет
                            равнодушных людей, мы
                            всегда готовы оперативно
                            подключиться и помочь друг
                            другу на всех этапах проекта.</p>
                    </div>
                    <div class="benefits__item">
                        <span class="benefits__subtitle">Нетворкинг (расширение круга общения, полезные знакомства) </span>
                        <p>Наше сообщество состоит из
                            очень разных людей. Это
                            помогает нам обмениваться
                            опытом, смотреть на
                            проблемы под разным углом,
                            находить слабые места в
                            организации или системе.
                            Поэтому, мы всегда рады
                            новым участникам.</p>
                    </div>
                    <div class="benefits__item">
                        <span class="benefits__subtitle">Площадка для обмена опытом</span>
                        <p>Проектное сообщество
                            объединяет специалистов из
                            разных сфер деятельности в
                            системе столичного
                            здравоохранения. Участникам
                            предоставляется площадка,
                            на которой каждый имеет
                            возможность поделиться
                            своим профессиональным
                            опытом, а также перенять
                            опыт других участников.</p>
                    </div>
                    <div class="benefits__item">
                        <span class="benefits__subtitle">Работа в команде единомышленников</span>
                        <p>В сообществе вы можете
                            найти единомышленников,
                            которые поддержат ваши
                            стремления, поделятся опытом
                            и помогут в реализации идей.
                            Поэтому, проектная
                            деятельность сообщества
                            построена на командной
                            работе. В таком формате
                            можно по-настоящему
                            раскрыть свои таланты и
                            сконцентрироваться на том,
                            что действительно важно.</p>
                    </div>
                </div>
                <div id="application" class="application">
                   <a target="_blank" href="https://forms.gle/uS6EpnSqhv7xGwry5" style="background:#9a0000;" class="btn">Зарегистрироваться на ближайшее мероприятие</a>
                    <!--<a href="https://docs.google.com/forms/d/e/1FAIpQLSeHO7TzMOAojFyvhdRJBiqKQqJfybPcUoGzuu33YTc3tePVRA/viewform?usp=sf_link" target="_blank" style="background:#9a0000;" class="btn">Заполнить заявку на вступление в сообщество</a>
                    -->
                    <span class="application__title">Заявки принимаются только от сотрудников учреждений, подведомственных Департаменту здравоохранения города Москвы</span>
                </div>
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





<!-- Modal -->
<div class="modal login fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
       <div class="container" >
	       <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">


                <img class="logo-project mb-2" src="img/logo.svg" alt="Проектное сообщество">

                <div class="modal-title-text mb-2">Введите, пожалуйста, email и пароль, указанные вами при регистрации.</div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <input id="email" type="email" style="font-size: 18px" placeholder="Электронная почта" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <input id="password" placeholder="Пароль" style="font-size: 18px"  type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

						@if (Route::has('password.request'))
						<div class="form-group row mb-2">
                            <div class="col-md-12">
								<a class="usual" href="{{ route('password.request') }}">
									{{ __('Забыли пароль?') }}
								</a>
                            </div>
                        </div>
						 @endif

                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn big btn-primary">
                                    {{ __('Вход') }}
                                </button>
                            </div>
                        </div>

                        <div class="modal-title-text mb-2">Если вы еще не зарегистрированы, пройти регистрацию можно по <a class="usual" href="{{ route('register') }}">{{ __('ссылке') }}</a>.</div>
                    </form>


        </div>
    </div>
</div>
       </div>
      </div>

    </div>
  </div>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/swiper.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

@error('email')
<script>
        $( document ).ready(function() {
            $("#requestModal").modal('show');
        });

</script>
 @enderror

 @error('password')
<script>
    $( document ).ready(function() {
        $("#requestModal").modal('show');
    });

</script>
 @enderror
</body>
</html>
