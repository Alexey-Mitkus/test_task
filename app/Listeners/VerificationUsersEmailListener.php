<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Verified;

use App\Models\EventLog;
use App\Models\EventLogCategory;

class VerificationUsersEmailListener
{
    public function handle(Verified $event)
    {
        EventLog::create([
            'user_type' => get_class($event->user),
            'user_id' => $event->user->id,
            'target_type' => null,
            'target_id' => null,
            'category_id' => EventLogCategory::where('slug', 'verify-email')->first()->id,
            'event_type' => EventLog::CONST_EVENT_TYPE_CREATE,
            'old_values' => null,
            'new_values' => null,
            'processed_user_id' => null,
            'message' => null,
            'status_id' => EventLog::CONST_STATUS_CONFIRMED
        ]);
    }
}
