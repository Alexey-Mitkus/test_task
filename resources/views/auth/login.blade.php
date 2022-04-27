{{-- @extends('news.includes.app') --}}
@extends('layouts.lk')

@section('content')

<div class="container registration p-0">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="register__main">
                <div class="login__picture"></div>
                <div class="login__form">
                    <h3>Авторизация</h3>
                    <p>Введите, пожалуйста, email и пароль, указанные вами при регистрации</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="field-block mb20">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input @error('email') border-red @enderror" type="text" 
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <label>E-mail</label>
                                <span class="@error('email') error-icon @enderror"></span>
                                @error('email') 
                                    <span class="form-desc alert-msg">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="field-block mb52">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input @error('password') border-red @enderror" type="password" name="password" required>
                                <label>Пароль</label>
                                <span class="@error('password') error-icon @enderror"></span>
                                @error('password') 
                                    <span class="form-desc alert-msg">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="ui-kit__check-item login__remember mr-0">
                            <div>
                                <input id="check" name="remember" value="check" type="checkbox" class="ui-kit__custom-checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="check" class="register-check">Запомнить меня</label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                    <a class="fogot-pass" href="{{ route('password.request') }}">
                                        {{ __('Забыли пароль?') }}
                                    </a>
                            @endif
                        </div>

                        <div class="kb-btns-line">
                            <button type="submit" class="ui-btn-red mr-4">Войти</button>
                            <div class="to-register">
                                <span>Новый участник сообщества?</span>
                                <a href="{{ route('register') }}">Регистрация</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-4">
            <div class="card">
                <div class="card-header">{{ __('Вход') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('Эл. адрес') }}</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Пароль') }}</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Запомнить меня') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Вход') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Забыли пароль?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
