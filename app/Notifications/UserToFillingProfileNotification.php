<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserToFillingProfileNotification extends Notification
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
            ->subject('Получите полный доступ к онлайн-площадке сообщества')
            ->view('mail.users.filling-to-profile', [
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
