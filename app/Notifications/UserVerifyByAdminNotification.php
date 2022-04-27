<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserVerifyByAdminNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $authuser;

    public function __construct($user, $authuser)
    {
        $this->user = $user;
        $this->authuser = $authuser;
    }

    public function via($notifiable)
    {
        return [
            'mail',
            'database'
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Ваш профиль прошел подтверждение')
            ->view('mail.users.verify-by-admin', [
                'user' => $this->user->withFields(),
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->authuser->withFields()->toArray(),
            'sender' => \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->withFields()->toArray()
        ];
    }

    public function toArray($notifiable)
    {
        return [
            
        ];
    }
}
