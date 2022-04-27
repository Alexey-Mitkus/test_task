<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KnowledgeBaseApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $post;
    private $authuser;

    public function __construct($post, $user)
    {
        $this->post = $post;
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
            ->subject('Ваш материал прошел проверку и был опубликован')
            ->view('mail.users.approved-knowledge-base-post', [
                'post' => $this->post,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->authuser->withFields()->toArray(),
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
