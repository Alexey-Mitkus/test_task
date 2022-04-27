<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $text;
    protected $owner;

    public function __construct($subject, $message, $sender)
    {
        $this->title = $subject;
        $this->text = $message;
        $this->owner = $sender;
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
            ->subject('У вас новое уведомление на сайте Проектного сообщества')
            ->view('mail.users.new-notification', [
                'title' => $this->title,
                'text' => $this->text,
                'owner' => $this->owner,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'subject' => $this->title,
            'message' => $this->text,
            'sender' => $this->owner,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
