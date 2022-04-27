@extends('layouts.lk')

@section('content')

<div class="container p-0">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="register__main">
                <div class="login__picture"></div>
                <div class="login__form">
                    <h3>Восстановление пароля</h3>
                    <p>Введите e-mail, указанный при регистрации. 
                        На указанный адрес будет выслано письмо со ссылкой для восстановления пароля</p>
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="field-block mb52">
                            <div class="ui-kit-input"> 
                                <input class="iu-input_input @error('email') border-red @enderror" type="email" name="email"
                                    value="{{ old('email', '') }}" required autocomplete="email" autofocus>


                                <label>E-mail</label>
                                <span class="@error('email') error-icon @enderror"></span>
                                @error('email') 
                                    <span class="form-desc alert-msg">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{--  --}}

 
                        {{--  --}}

                        <div class="kb-btns-line">
                            <button type="submit" class="ui-btn-red mr-4">Отправить</button>
                        </div>
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
                <div class="card-header">{{ __('Восстановить пароль') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Эл. адрес') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Отправить ссылку восстановления пароля') }}
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
