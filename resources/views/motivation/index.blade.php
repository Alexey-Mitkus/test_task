@extends('layouts.main-layout')

@section('meta_arr')

    <meta property="og:url"                content="https://community.mosgorzdrav.ru/passport" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="Цифровой паспорт" />
    {{--    <meta property="og:description"        content="How much does culture influence creative thinking?" />--}}
    <meta property="og:image"              content="{{ asset('/img/lk/header.jpg') }}" />

@endsection

@section('content')
    <div class="knowledge-base__main">

        <div class="row justify-content-center landing">
            {{-- Left menu side menu--}}
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            
            {{-- Motivation --}}
            <div class="col-lg-9 col-md-12 mt-4 pb-5">
                {{-- vue component --}}
                <motivation-index></motivation-index>                 
            </div>
        </div>
    </div> 

@endsection