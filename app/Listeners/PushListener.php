<?php

namespace App\Listeners;

use App\Events\PushEvent;
use App\Services\WePushService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PushEvent  $event
     * @return void
     */
    public function handle(PushEvent $event)
    {
        //

        $type = $event->type;
        $message_data = $event->message_data;
        $open_id = $event->open_id;
        WePushService::send_notice($type,$message_data,$open_id);
    }
}
