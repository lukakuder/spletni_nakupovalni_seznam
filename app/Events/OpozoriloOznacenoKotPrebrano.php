<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;

class OpozoriloOznacenoKotPrebrano implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $user;
    public $neprebranaOpozorila;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->neprebranaOpozorila = $user->opozorila()->where('prebrano', false)->count();
    }

    public function broadcastOn()
    {
        return new Channel('opozorila.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return ['neprebranaOpozorila' => $this->neprebranaOpozorila];
    }
}
