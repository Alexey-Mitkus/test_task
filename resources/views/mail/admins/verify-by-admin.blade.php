@extends('layouts.mail.default')
@section('content')
<p>Пользователь: <strong>{{ $user->full_name}}</strong></p>

@if( !empty($user->job) )
    <p style="margin-bottom: 0;">Место работы: <br>
        <strong>{{ ( !empty($user->job->organization) ? $user->job->organization->name : $user->job->raw_organization) }}</strong>
    </p>
@endif
@if( !empty($user->managment) && !empty($user->managment->role) )
    <p style="margin-bottom: 0;">Должность:
        <b>{{ ( !empty($user->managment->role) ? $user->managment->role->name : '' ) }}</b>
    </p>
@endif 
<p style="margin-bottom: 0;">Заполнил профиль на <b>{{ $user->rating }}%</b> и ожидает верификацию.</p>
<p>Для того чтоб верифицировать участника пройдите по ссылке: <br>
    <a target="_blank" href="{{ url(route('participant.show', $user)) }}">{{ url(route('participant.show', $user)) }}</a>
</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td></td>
            <td style="background-color: #9A1B1F; width: 223px;height: 40px; border-radius: 30px; padding: 5px;">
                <center>
                    <b>
                        <a href="{{ url(route('participant.show', $user)) }}" style="text-decoration: none; color:#ffffff; font-family: Helvetica, sans-serif;">Перейти к верификации</a>
                    </b>
                </center>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
@endsection