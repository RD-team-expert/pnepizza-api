<?php

namespace App\Events;

use App\Models\Acquisition;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcquisitionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $acquisition;

    /**
     * Create a new event instance.
     * @param Acquisition $acquisition
     */
    public function __construct(Acquisition $acquisition)
    {
        $this->acquisition = $acquisition;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
