@extends('layouts.mail.default')
@section('content')
<h4 style="font-family: 'Fira Sans', Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 900; line-height: 46px; letter-spacing: -0.6px; color: #151515; text-align: center;" valign="top">Новый пользователь!</h4>
<p>&nbsp;</p>
<p><b>Имя: </b>{{ $user->fields()->whereSlug('first_name')->first()->pivot->value }}</p>
<p><b>Эл. почта: </b>{{ $user->email }}</p>
@endsection