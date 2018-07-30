<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class StoreDeliverEvent
{
    use InteractsWithSockets, SerializesModels;

    protected $plat_order_id="";


    /**
     * Create a new event instance.
     *
     * @return void
     */


    public function __construct($plat_order_id)
    {
        //
        $this->plat_order_id=$plat_order_id;
    }

    public function getPlatOrderID(){
        return $this->plat_order_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
