@extends('layouts.mail.default')
@section('content')
<h3>Здравствуйте!</h3>
<p>&nbsp;</p>
<p>Для завершения регистрации, подтвердите, пожалуйста, свой адрес электронной почты и согласие на получение новостей</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td></td>
            <td style="background-color: #9A1B1F; width: 223px;height: 40px; border-radius: 30px; padding: 5px;">
                <center>
                    <b>
                        <a href="{{$verification_url}}" style="text-decoration: none; color:#ffffff; font-family: Helvetica, sans-serif;">Подтвердить</a>
                    </b>
                </center>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>
<p>Если вы не создавали аккаунт на <a href="{!! url(route('home')) !!}" target="_blank">{!! url(route('home')) !!}</a>, просто проигнорируйте это письмо.</p>
@endsection