<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\VerifyUserByAdminEvent;

use App\Models\EventLog;
use App\Models\EventLogCategory;

class VerifyUserByAdminListener
{
    public function handle(VerifyUserByAdminEvent $event)
    {
        EventLog::create([
            'user_type' => get_class($event->user),
            'user_id' => $event->user->id,
            'target_type' => null,
            'target_id' => null,
            'category_id' => EventLogCategory::where('slug', 'verify-admin')->first()->id,
            'event_type' => EventLog::CONST_EVENT_TYPE_CREATE,
            'old_values' => null,
            'new_values' => null,
            'processed_user_id' => null,
            'message' => null,
            'status_id' => EventLog::CONST_STATUS_CONFIRMED
        ]);

        if( $event->user->rating >= 25 )
        {
            $event->user->notify(new \App\Notifications\UserVerifyByAdminNotification($event->user, $event->authUser));
        }else{
            $event->user->notify(new \App\Notifications\UserVerifyByAdminWithFillProfileNotification($event->user, $event->authUser));
        }

        EventLog::create([
            'user_type' => get_class($event->user),
            'user_id' => $event->user->id,
            'target_type' => null,
            'target_id' => null,
            'category_id' => EventLogCategory::where('slug', 'get-role-community-member')->first()->id,
            'event_type' => EventLog::CONST_EVENT_TYPE_CREATE,
            'old_values' => null,
            'new_values' => null,
            'processed_user_id' => null,
            'message' => null,
            'status_id' => EventLog::CONST_STATUS_CONFIRMED
        ]);
    }
}
