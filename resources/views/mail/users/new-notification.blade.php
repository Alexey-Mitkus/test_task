@extends('layouts.mail.default')
@section('content')
<p>Здравствуйте {{$notifiable->full_name_short}}!</p>
<p>У вас новое уведомление на сайте Проектного сообщества.</p>
<p>
    <strong>{{ $title }}</strong>
</p>
<p>{{ $text }}</p>
<p>&nbsp;</p>
<p>Для просмотра уведомления на сайте сообщества перейдите по <a href="{{ route('user.notification.index') }}"style="color: #9A1B1F; text-decoration: underline;">ссылке</a> или кликните на кнопку ниже</p>
<p>&nbsp;</p>
<p>&nbsp;</p>      
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td></td>
            <td style="background-color: #9A1B1F; width: 223px;height: 40px; border-radius: 30px; padding: 5px;">
                <center>
                    <b>
                        <a href="{{ route('user.notification.index') }}" style="text-decoration: none; color:#ffffff;font-family: Helvetica, sans-serif;">Перейти в уведомления</a>
                    </b>
                </center>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>  
@endsection