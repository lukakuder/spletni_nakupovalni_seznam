<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GroupCreatedNotification extends Notification
{
    // Podatki, ki jih boste poslali v obvestilu
    public $group;

    public function __construct($group)
    {
        $this->group = $group;
    }

    public function via($notifiable)
    {
        // Definirajte kanale za obveščanje (mail, database, itd.)
        return ['database', 'mail']; // Pošljemo obvestilo preko baze in e-pošte
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Skupina ustvarjena')
            ->line('Uspešno ste ustvarili novo skupino: ' . $this->group->name)
            ->action('Ogledajte svojo skupino', url('/groups/' . $this->group->id))
            ->line('Hvala, da uporabljate našo aplikacijo!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'group_name' => $this->group->name,
            'message' => 'Uspešno ste ustvarili novo skupino.',
        ];
    }
}
