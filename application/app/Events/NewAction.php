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
use App\Models\Action;

class NewAction implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $nickname;
    public $players;
    public $round;
    public $bet_round;
    public $id;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Action $action)
    {
        $this->action=$action->toJson();
        $tournament=$action->player->tournament;
        $this->players=$tournament->players->toJson();
        $this->bet_round=$tournament->currentRound->currentBetRound->toJson();
        $this->round=$tournament->currentRound->toJson();
        $this->nickname=$action->player->user->nickname;

        $this->id=$action->player->tournament_id;
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
