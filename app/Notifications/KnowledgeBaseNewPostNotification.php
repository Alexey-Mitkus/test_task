<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KnowledgeBaseNewPostNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $post;

    public function __construct($post, $user)
    {
        $this->post = $post;
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
            ->subject('Пользователь предложил материал в базу знаний')
            ->view('mail.admins.new-knowledge-base', [
                'user' => $this->user->toArray(),
                'post' => $this->post,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'post' => $this->post->toArray()
        ];
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
