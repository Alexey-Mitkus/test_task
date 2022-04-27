@extends('layouts.lk')

@section('content')
<div class="container registration p-0">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="register__main">
                <div class="login__picture"></div>
                <div class="login__form">
                    <h3>Новый пароль</h3>
                    <p>Укажите новый ноавый пароль и поддтвердите его во втором поле</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        
                        <div class="field-block mb52">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input @error('password') border-red @enderror" type="password" 
                                    name="password" value="{{ old('password') }}" required autocomplete="new-password">
                                <label>Новый пароль</label>
                                <span class="@error('password') error-icon @enderror"></span>
                                @error('password') 
                                    <span class="form-desc alert-msg">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="field-block mb52">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input @error('password_confirmation') border-red @enderror" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" type="password" required autocomplete="new-password">
                                <label>Подтвердите пароль</label>
                                <span class="@error('password_confirmation') error-icon @enderror"></span>
                            </div>
                        </div>

                        <div class="kb-btns-line">
                            <button type="submit" class="ui-btn-red mr-4">Отправить</button>
                        </div>
                        <input type="hidden" name="token" value="{{ $token ?? old('token') }}" />
                        <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Восставновить пароль') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Эл. адрес') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Пароль') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Подтвердить пароль') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Восстановить пароль') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
