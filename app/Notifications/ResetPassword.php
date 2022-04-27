<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;

class ResetPassword extends Notification
{
    public function toMail($notifiable)
    {
        if( static::$toMailCallback )
        {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if( static::$createUrlCallback )
        {
            $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        } else {
            $url = url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        }

        return (new MailMessage)
            ->subject('Восстановление пароля')
            ->view('mail.users.reset-password', [
                'url' => $url,
                'notifiable' => $notifiable
            ]);
    }
}
