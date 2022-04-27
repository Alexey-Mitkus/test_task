@extends('layouts.mail.default')
@section('content')
<h4 style="font-family: 'Fira Sans', Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 900; line-height: 46px; letter-spacing: -0.6px; color: #151515; text-align: center; margin: 0; font-size: 30px;" valign="top">Новый запрос!</h4>
<p>&nbsp;</p>
<p><strong>Имя: </strong>{{$ticket['name']}}</p>
<p><strong>Организация: </strong>{{$ticket['organization']}}</p>
<p><strong>номер телефона: </strong>{{$ticket['telephone']}}</p>

@if( $ticket['type'] === 'consulting' )
    <p><strong>Тип:</strong> Консультация</p>
    <p><strong>Статус проекта:</strong> {{ $ticket['fields']['projectStatus']}}</p>

    <hr />

    @if( !empty($ticket['fields']['comment']) )
        <p><strong>Комментарий:</strong> {{ $ticket['fields']['comment'] }} </p>    
    @else
        <p>Комментария нет.</p>
    @endif
@else
    <p><strong>Тип:</strong> Исследование</p>

    @if($ticket['fields']['person'] === 'participant' )
        <p><strong>Статус - Участник</strong></p>
    @else
        <p><strong>Статус - Организатор</strong></p>
        <p><strong>Тема:</strong> {{ $ticket['fields']['theme']}}</p>
    @endif

@endif

@endsection