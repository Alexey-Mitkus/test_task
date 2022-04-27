@extends('layouts.news')
@section('content')
    <div class="container p-0">
        <div class="row justify-content-center landing">
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            <div class="col-lg-9 col-md-12 mt-4">
                <news-index></news-index>
            </div>
        </div>
    </div>
@endsection