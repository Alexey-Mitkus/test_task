<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use App\Events\VerifyUserByAdminEvent;

use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\RegisteredUsersListener;
use App\Listeners\VerificationUsersEmailListener;
use App\Listeners\VerifyUserByAdminListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            RegisteredUsersListener::class,
        ],
		Verified::class => [
            VerificationUsersEmailListener::class,
        ],
        VerifyUserByAdminEvent::class => [
            VerifyUserByAdminListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
