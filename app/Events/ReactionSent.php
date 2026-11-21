<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ReactionSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public string $emoji, public string $name) {}

    public function broadcastOn(): array
    {
        return [new Channel('wedding-chat')];
    }
}
