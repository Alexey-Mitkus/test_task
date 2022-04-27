<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNoFillProfileNoVerifyNotification extends Notification
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
            ->subject('Благодарим за регистрацию и рассказываем как получить полный доступ')
            ->view('mail.users.no-fill-profile-no-verify', [
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
