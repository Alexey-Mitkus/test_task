@extends('layouts.mail.default')
@section('content')
<h3>Здравствуйте {{$user->full_name}}!</h3>
<p>&nbsp;</p>
<p>Ваш профиль успешно прошел верификацию.</p>
<p>Осталось заполнить более подробно информацию о себе - и вам откроется полный доступ к онлайн площадке.</p>
<p>Для заполнения профиля перейдите по <a href="{{ route('user.index') }}" style="color: #9A1B1F; text-decoration: underline;">ссылке</a> или кликните на кнопку ниже</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td></td>
            <td style="background-color: #9A1B1F; width: 223px;height: 40px; border-radius: 30px; padding: 5px;">
                <center>
                    <b>
                        <a href="{{ route('user.index') }}" style="text-decoration: none; color:#ffffff; font-family: Helvetica, sans-serif;">Перейти в профиль</a>
                    </b>
                </center>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
@endsection