@extends('layouts.mail.default')
@section('content')
<div>
    <p><b>{{$data['first_name'] . ' ' . $data['last_name']}}</b> хочет стать спикером на тему: {{$data['operation']}},</p>

    @if( !empty($data['yourtheme']) )
        <p>{{$data['yourtheme']}}<p>
    @endif

    <p>Желаемые форматы:</p>

    @if( !empty($data['themes']) )
    <ul>
        @foreach($data['themes'] as $key => $theme)
            <li>{{ $theme }}</li>
        @endforeach 
    </ul>
    @endif

    <p>Номер телефона: <b>{{$data['number']}}</b>.</p>
    <p>Электронная почта: <b>{{$data['email']}}</b>.</p>
</div>
@endsection