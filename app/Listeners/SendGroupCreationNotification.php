<?php

namespace App\Listeners;

use App\Events\GroupCreatedEvent;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class SendGroupCreationNotification
{
    public function handle(GroupCreatedEvent $event)
    {
        $group = $event->group;
        $members = $group->users;

        foreach ($members as $member) {
            Notification::create([
                'user_id' => $member->id,
                'message' => "Dodani ste bili v novo skupino: {$group->name}",
                'prebrano' => false,
            ]);
        }

        Log::info('Obvestila so bila poslana članom skupine.', ['group' => $group->name]);
    }
}
