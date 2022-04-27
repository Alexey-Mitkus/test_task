@extends('layouts.lk')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-3 pt-4 d-md-none d-lg-block">
        <x-side-bar />
    </div>
    <div class="col-lg-9 col-md-12 pt-4">
        <dashboard-knowledgebase-index user='@json($user)' />
    </div>
</div>
@endsection