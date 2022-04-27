@extends('layouts.mail.default')
@section('content')
<p><strong>{{ $user['last_name']->pivot->value }} {{ $user['first_name']->pivot->value }}</strong> предложил(а) добавление нового материала в базу знаний,</p>
<p>&nbsp;</p>
<p><strong>Название материала:</strong> {{ $post->name }}</p>
<p><strong>Тема(ы):</strong></p>
@if( $post->themes()->whereNull('parent_id')->count() )
    <ul>
        @foreach($post->themes()->whereNull('parent_id')->get() as $key => $theme)
            <li>{{ $theme->name }}</li>
        @endforeach
    </ul>
@endif
<p><strong>Описание материала:</strong></p>
<p>{{ $post->description }}</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td></td>
            <td style="background-color: #9A1B1F; width: 223px;height: 40px; border-radius: 30px; padding: 5px;">
                <center>
                    <b>
                        <a href="{{ url(route('dashboard.knowledge-base.index')) }}" style="text-decoration: none; color:#ffffff; font-family: Helvetica, sans-serif;">Ссылка на админку</a>
                    </b>
                </center>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>
@endsection