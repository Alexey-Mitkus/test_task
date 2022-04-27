@extends('layouts.main-layout')
@section('content')
    <div class="statspikerom__main">

        <div class="row justify-content-center landing">
            {{-- Left menu side menu--}}
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            
            {{-- Content --}}
            <div class="col-lg-9 col-md-12 mt-4">
                <div class="statspikerom__content">
                    <h1 class="statspikerom__title">Поделитесь своими знаниями</h1>

                    <div class="statspikerom__baner">
                        <img src="{{asset('/storage/images/speakers/statspikerom_background-fon.png')}}" alt="baner">
                    </div>
                    {{-- 
                        <span class="statspikerom__subcripe">Фото: Karolina Grabowska/Pexels</span> --}}

                    <p class='statspikerom__desc'>
                        Сотрудничество — одна из ключевых ценностей нашего сообщества. 
                        Мы стремимся к тому, чтобы участники делились своими знаниями и 
                        опытом, обучали и развивали друг друга. Если вы готовы перенять 
                        эстафету знаний, добро пожаловать в ряды наших спикеров!
                    </p>

                    
                    <form action="{{ route('speaker.store') }}" method="post">
                        <h2 class="statspikerom__title-h2">О чем вы хотите рассказать?</h2>
                        @csrf

                        {{-- TAGS ARIA --}}
                        <div class="statspikerom__tags-aria">
                            {{-- tags aria vue component SpikerForm.vue --}}
                            <speakers-form />
                        </div>

                        <h2 class="statspikerom__title-h2">Как вы хотите это сделать?</h2>

                        {{-- CHECKBOXES --}}
                        <div class="statspikerom__check-block">
                            <div class="statspikerom__check-item">
                                <input id="vebin" name="theme1" value="Вебинар" type="checkbox" class="statspikerom__custom-checkbox">
                                <label for="vebin">Вебинар</label>
                            </div>
                            <div  class="statspikerom__check-item">
                                <input id="event" name="theme2" value="Очное мероприятие" type="checkbox" class="statspikerom__custom-checkbox">
                                <label for="event">Очное мероприятие</label>
                            </div>
                            <div class="statspikerom__check-item">
                                <input id="podcast" name="theme3" value="Подкаст" type="checkbox" class="statspikerom__custom-checkbox">
                                <label for="podcast"> Подкаст</label>
                            </div>
                            <div  class="statspikerom__check-item">
                                <input id="article" name="theme4" value="Статья/Интервью" type="checkbox" class="statspikerom__custom-checkbox">
                                <label for="article">Статья/Интервью</label>
                            </div>
                        </div>

                        {{-- FORM OF "LEAVE YOYR CONTACT" --}}
                        {{-- title of "leave your cantact" --}}
                        <div class="statspikerom__talk-about">
                            <h2 class="statspikerom__title-h2">Оставьте ваш контакт</h2>
                            <p>Мы свяжемся с вами и ответим на все вопросы</p>
                        </div>
                            
                        {{-- form of "leave your contact" --}}
                        <div class="statspikerom-contacts">
                            <input placeholder="Имя" name="first_name" class="form-control be_input" type="text" required>
                        
                            <input placeholder="Фамилия" name="last_name" class="form-control be_input" type="text" required>

                            <input placeholder="Электронная почта" name="email" class="form-control be_input" type="email" required>
                        
                            <input id="mobileinput" placeholder="Телефон" name="number" class="form-control be_input" type="number" required>
                        </div>
                            
                        <button class="statspikerom__btn-big" type="submit">Отправить
                        </button>
                    </form>
                </div>

                {{-- OUR SPIKERS BLOCK --}}
                <div class="statspikerom__spikers">

                    <h2>Наши спикеры и записи выступлений</h2>
                        
                    {{-- SPIKERS CARDS --}}
                    <speakers-lists />
                </div>
            </div> 
        </div>
    </div>

    {{-- Модальное окно --}}
    @if(Session::has('info'))
    <div class="services__wrapper">

        <div id="popup" class="popup open">
            <div class="popup__body">
                <div class="popup__content-services popup-show">
                    <img src="{{ asset('/images/close_popup.svg') }}" alt="" class="popup__close close-popup">
    
                    <div class="services__popup-content">
                        <div class="services__popup-done">
                            <img src="{{ asset('/images/services/done-popup.svg') }}" alt="">
                            <h2>Спасибо, ваша заявка принята!</h2>
                            
                            <p>Мы свяжемся с вами в ближайшее время.</p>
    
                            <button class="close-popup">Хорошо</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection