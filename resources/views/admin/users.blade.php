@extends('layouts.lk')

@section('content')

	{{-- <div class="container">
		<div class="lk_announcement mt-3 mb-3">
			<span class="warning_icon_circle desktop"><span class="warning_icon"></span></span>
			<p class="text">
			В данный момент сайт работает в тестовом режиме. Если у вас есть предложения по его улучшению или вы заметили ошибку, пожалуйста, напишите об этом на почту <a href="mailto:{{ env('MAIL_ADMINISTRATOR_EMAIL') }}">{{ env('MAIL_ADMINISTRATOR_EMAIL') }}</a>
			</p>
		</div>
	</div> --}}
    <div class="row justify-content-center">
	    
	    <div class="col-lg-3 pt-4 d-md-none d-lg-block">
			<x-side-bar />
		</div>
		<div class="col-lg-9 col-md-12 pt-4">
        	<admin-users users="{{$users}}" user="{{$user}}"></admin-users>
        </div>
    </div>
</div>

@endsection
