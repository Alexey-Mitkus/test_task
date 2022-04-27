@extends('layouts.main-layout')
@section('content')
    <div class="knowledge-base__main">

        <div class="row justify-content-center landing">
            {{-- Left menu side menu--}}
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            
            {{-- Conntent --}}
            <div class="col-lg-9 col-md-12 mt-4 pb-5">
                <div class="knowledge-base__header">
                    <div class="knowledge-base__header-content">
                        <h1>База знаний</h1>
                        <h3>Здесь вы найдете материалы о проектном и продуктовом управлении, 
                            информацию об инструментах, методологиях и сертификациях, шаблоны 
                            проектной документации и примеры лучших практик
                        </h3>
                    </div>
                    <img class="knowledge-base__header-logo" src="{{ asset('/images/knowledge-base/knowleadge-base_header-logo.png') }}" alt="">
                </div>
                <knowledgebase-index :auth="{{$auth}}"></knowledgebase-index>
            </div>
        </div>
    </div> 

@endsection