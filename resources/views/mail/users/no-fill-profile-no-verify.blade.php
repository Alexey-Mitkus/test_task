@extends('layouts.mail.default')
@section('content')
<h3>Здравствуйте {{$user->full_name}}!</h3>
<p>&nbsp;</p>
<p>Благодарим за регистрацию на сайте Проектного сообщества.</p>
<p>Мы проверяем ваш профиль, чтобы предоставить полный доступ к онлайн-площадке. Обычно это занимает не больше 1 рабочего дня. А пока вы можете дозаполнить профиль, чтобы другие участники узнали о вас больше.</p>
<p>Для заполнения профиля перейдите по <a href="{{ url(route('user.index')) }}" style="color: #9A1B1F; text-decoration: underline;">ссылке</a> или кликните на кнопку ниже</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td></td>
            <td style="background-color: #9A1B1F; width: 223px;height: 40px; border-radius: 30px; padding: 5px;">
                <center>
                    <b>
                        <a href="{{ route('user.index') }}" style="text-decoration: none; color:#ffffff; font-family: Helvetica, sans-serif;">Перейти в настройки</a>
                    </b>
                </center>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
@endsection