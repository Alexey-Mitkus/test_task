@extends('layouts.lk')
@section('content')
	<div class="row justify-content-center"> 
		<div class="col-lg-3 pt-4 d-md-none d-lg-block">
			<x-side-bar />
		</div>
		<div class="col-lg-9 col-md-12 pt-4">	
			<div class="row">
				<div class="col-md-12">
					<div class="box-search">
						@if( $events->count() )
							@foreach($events as $key => $event)
								<div class="box-module border_bottom p-2">
									<div class="row">
										<div class="col-md-1 pr-0">
											<div class="avatar-circle tinytiny">
												<a href="{{ route('participant.show', $event->user) }}" class="ratio img-responsive img-circle avatar"{!! ( !empty($event->user->avatar) ? 'style="background-image:url(\'' . $event->user->avatar . '\');background-size: contain;"' : '' ) !!}></a>
											</div>
										</div>
										<div class="col-md-11">
											<div class="user_title small_description">
												<a class="font-weight-bold" href="{{ route('participant.show', $event->user) }}">
													{{ $event->user->full_name }}
												</a>

												@if( $event->target )
													@switch($event->target_type)
														@case('App\Models\User')
														@case('App\\Models\\User')
															Внёс изменения для <a href="{{ route('participant.show', $event->target) }}" target="_blank">{{ $event->target->full_name }}</a>
														@break
														@default
														@break
													@endswitch
												@endif

												@switch($event->category->slug)
													@case('verify-email')
														{!! $event->category->name !!}
													@break
													@case('registration')
														{!! $event->category->name !!}
													@break
													@default
													@break
												@endswitch
											</div>
											<div class="mt-1 date_text">
												<div class="grey_text">Время обновления: <strong>{{ \App\Libraries\Date\DateFormat::post($event->created_at) }}</strong></div>
											</div>
										</div>
									</div>
								</div>
							@endforeach
							<div class="row">
								<div class="col-md-12 align-center">{{ $events->withQueryString()->links() }}</div>
							</div>
						@else
							<div class="box-module border_bottom p-4">
								<div class="row">
									<div class="col-md-12">
										<i>Пока нет обновлений.</i>
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection