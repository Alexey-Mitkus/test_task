{{-- @extends('layouts.lk') --}}
@extends('layouts.main-layout')

@section('content')

<div class="container p-0">
    <div class="row justify-content-center">

	    <div class="col-lg-3 d-none d-lg-block pt-4">
		    <x-side-bar />
		</div>
		<div class="col-lg-9 col-md-12 pt-4">
        	<users-notifications-index :user='@json($user)' />
        </div>
    </div>
</div>

@endsection
