@extends('layouts.main-layout')
@section('content')
    <div class="user-lk">
        <div class="row justify-content-center landing">
            {{-- Left menu side menu--}}
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            {{-- Conntent --}}
            <div class="col-lg-9 col-md-12 mt-4 pb-5">
                <users-index status="user" :data='@json($user)' />
            </div>
        </div>
    </div> 
@endsection