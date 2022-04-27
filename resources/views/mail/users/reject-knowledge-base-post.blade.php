@extends('layouts.mail.default')
@section('content')
<p>Здравствуйте!</p>
<p>&nbsp;</p>
<p>К сожалению, ваш материал не может быть добавлен в Базу знаний.</p>
<p>&nbsp;</p>
<p>Причины отклонения публикации: {{ $reject }}</p>
<p>При необходимости вы можете внести корректировки и отправить нам материал еще раз.</p>
<ul>
    <li><strong>Название материала:</strong> {{ $post->name }}</li>
    <li><strong>Формат:</strong> {!! collect($post->formats->pluck('name'))->implode(', ') !!}</li>
    <li><strong>Тема:</strong> {!! collect($post->themes()->whereNull('parent_id')->pluck('name'))->implode(', ') !!}</li>
    <li><strong>Подкатегория:</strong> {!! collect($post->subthemes->pluck('name'))->implode(', ') !!}</li>
    <li><strong>Аннотация:</strong> {{ $post->description }}</li>
</ul> 
@endsection