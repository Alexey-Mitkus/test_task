<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification implements ShouldQueue
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
            'mail',
            'database'
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Новый пользователь!')
            ->view('mail.admins.new-user', [
                'user' => $this->user,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return $this->user->withFields()->toArray();
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
