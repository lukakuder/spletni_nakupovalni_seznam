<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Group;

class GroupCreatedEvent
{
    use Dispatchable, SerializesModels;

    public $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }
}
