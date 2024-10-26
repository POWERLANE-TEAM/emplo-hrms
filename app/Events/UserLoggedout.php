<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedout implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $authBroadcastId;

    /**
     * Create a new event instance.
     */
    public function __construct($authBroadcastId)
    {
        $this->authBroadcastId = $authBroadcastId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        // return [
        //     new PrivateChannel('channel-name'),
        // ];

        return new PrivateChannel('user_auth.' . $this->authBroadcastId);
    }

    public function broadcastWith()
    {
        return [
            'authBroadcastId' => $this->authBroadcastId,
        ];
    }
}
