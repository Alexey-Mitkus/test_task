<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
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
            ->subject('Новый запрос')
            ->view('mail.admins.new-ticket', [
                'ticket' => $this->ticket,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket' => $this->ticket->toArray(),
            'sender' => \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()->withFields()->toArray()
        ];
    }

    public function toArray($notifiable)
    {
        return [
            
        ];
    }
}
