<?php

namespace App\Events;

use App\DTO\liveDataDTO;
use App\Models\LiveData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchfinishedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public liveDataDTO $matchData;
    public function __construct(liveDataDTO $matchData)
    {
        $this->matchData = $matchData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('Match ended.',$this->matchData);
    }

    public function broadcastAs(): string
    {
        return 'match.ended';
    }
}
