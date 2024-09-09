<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedout implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $auth_broadcast_id;

    /**
     * Create a new event instance.
     */
    public function __construct($auth_broadcast_id)
    {
        $this->auth_broadcast_id = $auth_broadcast_id;
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

        return new PrivateChannel('user_auth.' . $this->auth_broadcast_id);
    }

    public function broadcastWith()
    {
        return [
            'auth_broadcast_id' => $this->auth_broadcast_id
        ];
    }
}
