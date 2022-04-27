@extends('layouts.main-layout')

@section('meta_arrow')
    <meta property="og:url"                content="{{route('verification.notice')}}" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="Регистрация" />
    <meta property="og:image"              content="{{ asset('/img/logo_1.png') }}" />

@endsection


@section('content')
<div class="container registration p-0">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="register__main">
                <!-- Form -->
                <div class="register-form verification">
                    <span class="step">Шаг 2 из 2</span>

                    <h3>Подтверждение электронной почты</h3>
                    <p class="verif-desc">Для завершения регистрации необходимо подтвердить адрес электронной почты.</p>

                    <p class="verif-desc">Мы направили письмо со ссылкой для подтверждения на <b>{{Auth::user()->email}}</b>. Пожалуйста, проверьте вашу почту и нажмите на ссылку в письме для подтверждения.</p>
                    
                    <hr>

                    <form class="verif-resent" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <span>Не получили письмо?</span><button type="submit">{{ __('Отправить письмо со ссылкой повторно') }}</button>.
                    </form>
                </div>

                <!-- Baner -->
                <div class="register-picture">
                    <h2>После регистрации на площадке вы получите доступ:</h2>
                    <ul>
                        <li>
                            <img src="/images/register/register-community.svg">
                            <span>К списку участников</span>
                        </li>
                        <li>
                            <img src="/images/register/register-news.svg">
                            <span>Анонсам мероприятий</span>
                        </li>
                        <li>
                            <img src="/images/register/register-helpers.svg">
                            <span>Цифровым помощникам</span>
                        </li>
                        <li>
                            <img src="/images/register/register-services.svg">
                            <span>Сервисам сообщества</span>
                        </li>
                        <li>
                            <img src="/images/register/register-knowledge-base.svg">
                            <span>Базе знаний</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
