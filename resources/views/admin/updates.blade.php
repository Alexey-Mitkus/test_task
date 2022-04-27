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
			<div class="row">
			<div class="col-md-12">     
			<div class="box-search pt-3 pl-3">
				@if($updates->count() == 0)
			    <div class="box-module border_bottom p-4">
				    <div class="row">
					    <div class="col-md-12">
						    <i>Пока нет обновлений.</i>
						</div>
					</div>
				</div>
			    @endif
				<div class="row">
					<div class="col-md-12 align-center">
						{{ $updates->links() }}
					</div>
				</div>
			    @foreach($updates as $update)
			    <div class="box-module border_bottom p-2">
				    <div class="row">
					    <div class="col-md-1 pr-0">
							<div class="avatar-circle tinytiny">
								<a href="{{ route('participant.show', $update->user) }}" class="ratio img-responsive img-circle avatar" style="background-image:url(/storage/{{$update->user->avatar}});background-size: contain;"></a>
							</div>
					    </div>
					    <div class="col-md-11">
						    <div class="user_title small_description">
							   <a class="font-weight-bold" href="{{ route('participant.show', $update->user) }}">{{$update->user->name}}</a> {!!$update->updateObj->title!!} 
							   @if($update->target) {{$update->target->name}} @endif
							   @if($update->updateObj->id == 9)
								   @if($update->actionable_type === 'App\Field')
									   <b>{{$update->actionable->name}}</b>
								   @endif
								   @if($update->actionable_id === 3)
								   <div class="difference mt-2">
									   <b>Было:</b> {{json_decode($update->original)->wo_place}}
								   </div>
								   <div class="difference mt-2">
									   <b>Стало:</b> {{json_decode($update->changes)->wo_place}}
								   </div>
								   @elseif($update->actionable_id === 14)
								   <div class="difference mt-2">
									   <b>Было:</b>
									   
									   <ul>
										   @foreach(json_decode($update->original) as $key => $val)
										   		
											   <li>
											   		{{$key}}: {{$val}}
											   </li>
										   		
										   @endforeach
										</ul>
									   
								   </div>
								   <div class="difference mt-2">
									   <b>Стало:</b> 
									   
									   <ul>
										   @foreach(json_decode($update->changes) as $key => $val)
										   		
											   <li>
											   		{{$key}}: {{$val}}
											   </li>
										   		
										   @endforeach
										</ul>
									   
									  
									   
									   
								   </div>
								   @else
								   <div class="difference mt-2">
									   <b>Было:</b> {{$update->original}}
								   </div>
								   <div class="difference mt-2">
									   <b>Стало:</b> {{$update->changes}}
								   </div>
								   @endif
								@endif
							</div>
						    						    
						    <div class="mt-1 date_text">
							    <div class="grey_text">Время обновления {{\Carbon\Carbon::parse($update->created_at)}}</div>
						    </div>
					    </div>
					</div>
			    </div>
			    @endforeach
			    
			</div>
			
			</div>
			
			</div>
			
        </div>
    </div>
</div>

@endsection
