<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KnowledgeBaseRejectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $post;
    private $reject;
    private $authuser;

    public function __construct($post, $reject, $user)
    {
        $this->post = $post;
        $this->reject = $reject;
        $this->authuser = $user;
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
            ->subject('Ваш материал не может быть добавлен в Базу знаний')
            ->view('mail.users.reject-knowledge-base-post', [
                'reject' => $this->reject,
                'post' => $this->post,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->authuser->withFields()->toArray(),
            'reject' => $this->reject,
            'post' => $this->post->toArray(),
            'sender' => \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->withFields()->toArray()
        ];
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
