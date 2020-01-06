<?php

namespace App\Listeners;

use App\Events\RoyaltyEvent;
use App\Services\RoyaltyService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RoyaltyListener
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
     * @param  RoyaltyEvent  $event
     * @return void
     */
    public function handle(RoyaltyEvent $event)
    {
        //
        $user_id = $event->user_id;
        $order_royalty_price = $event->order_royalty_price;
        $is_arrive = $event->is_arrive;
        $agent_id = $event->agent_id;

        RoyaltyService::HandleRoyalty($user_id,$order_royalty_price,$is_arrive,$agent_id);
    }
}
