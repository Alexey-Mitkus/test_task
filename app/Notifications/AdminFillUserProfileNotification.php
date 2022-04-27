<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminFillUserProfileNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return [
            'database',
            'mail'
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Пользователь заполнил профиль, но не верифицирован')
            ->view('mail.admins.verify-by-admin', [
                'user' => $this->user,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->user->withFields()->toArray(),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
