<?php

namespace App\Listeners;

use App\Notifications\SendNewRegisteredUserEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewRegisteredUserEmailNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event)
    {
        $event->user->notify(new SendNewRegisteredUserEmailNotification);
    }
}
