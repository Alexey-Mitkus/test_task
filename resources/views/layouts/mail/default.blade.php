<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    <table align="center" style="max-width: 760px; width: 100%; background-image: url('{{ asset('/img/email/email_background.png')}}');background-size: 70% auto;background-repeat: no-repeat;background-position: bottom right;font-family: 'PT Sans', Helvetica, Arial, sans-serif;color: #353432; padding: 20px;"
            border="0" cellpadding="0" cellspacing="0" >
        <tbody>
            <!-- header -->
            <tr>
                <td  style="padding: 5px; width: 160px; height: 55px">
                    <img src="{{ asset('/img/email/logo.png')}}" alt="logo" width="150px" height="45px" style="margin-bottom: 20px;">
                </td>
            </tr>
            <!-- content -->
            <tr>
                <td align="center" style="max-width: 600px; width: 100%; background-color: #dbdbdb;padding: 20px; min-height: 100px;border-radius: 10px;  text-align: left; line-height: 22px; font-size: 16px;font-family: Helvetica, Arial, sans-serif;"> 

                    {{-- CONTENT --}}
                    @yield('content')

                    {{-- <h2>Тема письма / заголовок</h2> --}}
                       
                    {{-- <p>ИО , здравствуйте!</p>
                    <p> Благодарим за регистрацию на сайте Проектного сообщества.</p>
                    <p>Для того, чтобы получить полный доступ к онлайн-площадке и узнать больше об 
                        участниках сообщества, заполните свой профиль не менее, чем на 50%.
                        Для заполнения профиля перейдите по <a href="">ссылке</a> или кликните на кнопку ниже.
                    </p> --}}
                    
                    {{-- <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td></td>
                                <td style="background-color: #9A1B1F; width: 223px; height: 40px; border-radius: 30px; padding: 10px">
                                    <center><b>
                                        <a href="" 
                                            style="text-decoration: none; color: 
                                            #ffffff; 
                                            font-family: Helvetica, sans-serif;">
                                        Заполни профиль</a>
                                    </b></center>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table> --}}
                    
                    {{-- <div clsss="triangle" style="
                        position: relative;
                        width: 0;
                        height: 0;
                        left: 29px;
                        top: 32px;
                        border-left: 10px solid transparent;
                        border-right: 10px solid transparent;
                        border-top: 15px solid #dbdbdb;">
                    </div> --}}
                </td>
            </tr>
            <!-- robot -->
            <tr>
                <td>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tbody>
                            <tr>
                                <td valign="middle" style="width: 85px;"> 
                                    <img src="{{ asset('/img/email/email_robot.png')}}" alt="robot" width="70" height="70" style="margin-left: 20px;">
                                </td>
                                <td>
                                    <p style=" 
                                        margin: 0; 
                                        display: block;
                                        font-style: normal;
                                        font-weight: 700;
                                        font-size: 15px;
                                        line-height: 20px; 
                                        color: #0D4552;
                                        padding-bottom: 10px;
                                        font-family: 'PT Sans', Helvetica, Arial, sans-serif;">
                                    C уважением, <br>ваш цифровой помощник Робот Гоша</p> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- border-->
            <tr>
                <td>
                    <hr style="position: absolute; width: 172px; height: 0px; border: 1px solid #DCDCDC;">
                </td>
            </tr>
            <!-- footer -->
            {{-- <tr>
                <td>
                    <!-- facebook -->
                    <table width="70%" style="margin-top: 20px;" border="0" cellpadding="0" cellspacing="0" > 
                        <tbody>
                            <tr>
                                <td style="width:32px; height:32px;">
                                    <img src="{{ asset('img/email/facebook.png')}}" alt="facebook" width="32px" height="32px">
                                </td>
                                <td>
                                    <p style="
                                        font-style: normal;
                                        font-weight: 400;
                                        font-size: 14px;
                                        line-height: 20px;
                                        color: #424242;
                                        margin-top: 4px;
                                        margin-bottom: 0px;
                                        margin-left: 10px;
                                        font-family: 'PT Sans', Helvetica, Arial, sans-serif;
                                    "><a href="https://www.facebook.com/groups/community.zdrav" style="color:#0a4551;">Вступайте в группу сообщества</a>, чтобы общаться и принимать участие в голосованиях. 
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>            --}}
            <tr>
                <td>
                    <!-- whatsapp -->
                    <table width="70%" border="0" cellpadding="0" cellspacing="0" >
                        <tbody>
                            <tr>
                                <td style="width:32px; height:32px;">
                                    <img src="{{ asset('/img/email/whatsapp.png')}}" alt="whatsapp"width="32px" height="32px">
                                </td>
                                <td>
                                    <p style="
                                        font-style: normal;
                                        font-weight: 400;
                                        font-size: 14px;
                                        line-height: 20px;
                                        color: #424242;
                                        margin-top: 4px;
                                        margin-bottom: 10px;
                                        margin-left: 10px;
                                        font-family: 'PT Sans', Helvetica, Arial, sans-serif;
                                    "><a href="https://chat.whatsapp.com/Km3aG28E5Qw8QJl65oIpS9" style="color:#0a4551;">Подписывайтесь на канал сообщества</a> в WhatsApp, чтобы получать уведомления о последних новостях. </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- link -->
            <tr>
                <td><a href="{{ url(route('home')) }}" style="font-family: 'PT Sans', Helvetica, Arial, sans-serif; color:#0a4551;">{{url(route('home'))}}</a></td>
            </tr>
            <!--  -->
            <tr>
                <td>
                    <p style="
                        display: block;
                        width:63%;
                        font-style: normal;
                        font-weight: 400;
                        font-size: 14px;
                        line-height: 20px;
                        color: #424242;
                        margin-bottom: 80px;
                        font-family: 'PT Sans', Helvetica, Arial, sans-serif;
                        "> Если вы просматриваете данное письмо в приложении Outlook и у вас не загружаются изображения, кликните по уведомлению вверху письма и нажмите "Скачать изображения" 
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>