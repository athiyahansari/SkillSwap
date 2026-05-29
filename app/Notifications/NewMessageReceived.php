<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Conversation;

class NewMessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public $conversation;
    public $senderName;

    public function __construct(Conversation $conversation, $senderName)
    {
        $this->conversation = $conversation;
        $this->senderName = $senderName;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'message' => 'You received a new message from ' . $this->senderName . '.',
            'type' => 'new_message',
            'url' => route('inbox.show', $this->conversation)
        ];
    }
}
