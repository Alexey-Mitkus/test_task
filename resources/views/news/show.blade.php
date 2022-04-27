@extends('layouts.news')
@section('content')
    <div class="container p-0">
        <div class="row justify-content-center landing">
            {{-- Left menu side menu--}}
            <div class="col-lg-3 pt-4 d-none d-lg-block">
                <x-side-bar />
            </div>

            {{-- News post --}}
            <div class="col-lg-9 col-md-12 mt-4 ">
                <div class="ml-0 mr-0 news__background news-post__content">
                    {{-- Заголовок --}}
                    <div class="col-lg-12 col-md-12">
                        <h1 class="news-post__title">
                            {{$news->title}}
                        </h1>
                    </div>

                    {{--  --}}
                    <div class="col-lg-12 col-md-12 pt-2">
                        <div href="#" class="text-dark datetext d-flex justify-content-between">
                            <a href="{{route('news.index')}}" id="news-get-category" class="news-post__article">
                                {{$news->labels[0]}}
                            </a>
                            <span class="news-post__date">
                                {{Date::parse($news->published)->format('d/m/Y г.')}}
                            </span>
                        </div>
                    </div>

                    {{-- Пост --}}
                    <div class="col-lg-12 col-md-12">
                        <div class="mb-3 posttext">
    
                            {!! $news->content !!}
    
                                <strong>Поделиться материалом в социальных сетях:</strong>
                                <!-- uSocial -->
                                <div class="uSocial-Share pb-5" data-pid="839e3f22da73b1ef157bdeb8dcc6f7bd" data-type="share" data-options="round-rect,style1,default,absolute,horizontal,size48,eachCounter0,counter0,nomobile" data-social="vk,ok,fb,telegram,wa"></div>
                                <!-- /uSocial -->     
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
<script async src="https://usocial.pro/usocial/usocial.js?uid=827cad0396f61639&v=6.1.5" data-script="usocial" charset="utf-8"></script>