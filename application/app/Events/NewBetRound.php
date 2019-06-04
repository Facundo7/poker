<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Models\BetRound;

class NewBetRound implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bet_round;
    public $round;
    public $players;
    public $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BetRound $bet_round)
    {
        $tournament=$bet_round->round->tournament;
        $this->id=$tournament->id;
        $this->bet_round=$bet_round->toJson();
        $this->round=$bet_round->round->tournament->currentRound->toJson();
        $this->players=$tournament->players->toJson();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tournament.'.$this->id);
    }
}
