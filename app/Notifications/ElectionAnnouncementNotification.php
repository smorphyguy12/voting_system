<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Election;

class ElectionAnnouncementNotification extends Notification
{
    private $election;

    public function __construct(Election $election)
    {
        $this->election = $election;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Election Announcement')
            ->line('A new election has been announced.')
            ->action('View Election', route('elections.show', $this->election->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'election_id' => $this->election->id,
            'message' => "New election '{$this->election->name}' has been announced"
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'election_id' => $this->election->id,
            'message' => "New election '{$this->election->name}' has been announced"
        ]);
    }
}