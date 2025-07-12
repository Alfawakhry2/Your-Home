<?php

namespace App\Events;

use App\Models\Estate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EstateAvailableForNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $estate ;
    /**
     * Create a new event instance.
     */
    public function __construct(Estate $estate)
    {
        $this->estate = $estate ;
    }

}
