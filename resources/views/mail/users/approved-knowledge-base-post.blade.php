@extends('layouts.mail.default')
@section('content')
<p>Здравствуйте!</p>
<p>&nbsp;</p>
<p>Ваш материал прошел проверку и был опубликован в Базе знаний. Вы сможете найти его по следующим параметрам:</p>
<p>&nbsp;</p>
<p>При необходимости вы можете внести корректировки и отправить нам материал еще раз.</p>
<ul>
    <li><strong>Название материала:</strong> {{ $post->name }}</li>
    <li><strong>Формат:</strong> {!! collect($post->formats->pluck('name'))->implode(', ') !!}</li>
    <li><strong>Тема:</strong> {!! collect($post->themes()->whereNull('parent_id')->pluck('name'))->implode(', ') !!}</li>
    <li><strong>Подкатегория:</strong> {!! collect($post->subthemes->pluck('name'))->implode(', ') !!}</li>
</ul>
<p>&nbsp;</p>
<p>Спасибо за участие в пополнении библиотеки сообщества.</p>
<p>Будем рады новым материалам.</p>
<p>Ссылка на базу знаний: <a href="{!! url(route('knowledge-base.index')) !!}">{{ url(route('knowledge-base.index')) }}</a></p>
@endsection