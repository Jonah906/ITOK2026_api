<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Chat $chat) {}

    public function broadcastOn(): array
    {
        return [new Channel('wedding-chat')];
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->chat->id,
            'message'    => $this->chat->message,
            'name'       => $this->chat->rsvp->name,
            'created_at' => $this->chat->created_at,
        ];
    }
}
