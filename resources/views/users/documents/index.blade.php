@extends('layouts.main-layout')
@section('content')
    <div class="my-documents__main">
        <div class="row justify-content-center landing">
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            <div class="col-lg-9 col-md-12 mt-4 pb-5">
                <div class="my-documents__header">
                    <div class="my-documents__header-content">
                        <h1>Мои Документы</h1>
                        <h3>Здесь хранятся все ваши идеи проектов, цифровые паспорта и другие относящиеся к вашим проектам документы.</h3>
                    </div>
                    <img src="{{ asset('/images/services/documents_header_imade.png') }}" alt="">
                </div>
                <div class="my-documents__content">
                    <users-documents-index :user='@json($user)' />                        
                </div>
            </div>
        </div>
    </div>
@endsection
