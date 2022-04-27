<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSpeakerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
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
            ->subject($this->data['first_name'] . ' ' . $this->data['last_name'] . ' хочет стать спикером')
            ->view('mail.admins.new-speaker', [
                'data' => $this->data,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return $this->data;
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
