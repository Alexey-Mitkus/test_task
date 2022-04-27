@extends('layouts.main-layout')
@section('content')
    <div class="container p-0">
        <div class="row justify-content-center landing">
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>
            <div class="col-lg-9 col-md-12 mt-4">
                <div class="digital-idea__header">   
                </div>
                <div class="digital-idea__content">
                    <div class="digital-idea__component">
                        <services-digitals-passports-edit :passport='@json($passport)' />
                    </div>
                </div>    
            </div>
        </div>
    </div>
@endsection