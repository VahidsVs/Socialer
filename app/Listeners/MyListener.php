<?php

namespace App\Listeners;

use App\Events\MyEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MyListener
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
    public function handle(MyEvent $event): void
    {
        //
        Log::debug("Username: {$event->username} -  Action: {$event->action}");
    }
}
