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
	    
	    <div class="col-md-2">
			<x-side-bar />
		</div>
		<div class="col-md-10">	
			<admin-updates updates="$updates" />
        </div>
    </div>
</div>

@endsection
