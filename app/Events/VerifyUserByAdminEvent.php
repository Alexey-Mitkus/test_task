<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerifyUserByAdminEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $authUser;

    public function __construct($user, $authUser)
    {
        $this->user = $user;
        $this->authUser = $authUser;
    }
}
