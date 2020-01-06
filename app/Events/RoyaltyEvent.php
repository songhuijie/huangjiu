<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RoyaltyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $user_id;
    public $order_royalty_price;
    public $is_arrive;
    public $agent_id;

    /**
     * Create a new event instance.
     * RoyaltyEvent constructor.
     * @param $user_id
     * @param $order_royalty_price
     * @param $is_arrive
     * @param $agent_id
     */
    public function __construct($user_id,$order_royalty_price,$is_arrive,$agent_id)
    {
        //
        $this->user_id = $user_id;
        $this->order_royalty_price = $order_royalty_price;
        $this->is_arrive = $is_arrive;
        $this->agent_id = $agent_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
