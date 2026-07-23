<?php

namespace App\Observers;

use App\Jobs\ProcessAiChatResponse;
use App\Models\Message;

class MessageObserver
{
    public function created(Message $message): void
    {
        // Only trigger AI auto-reply when the message is sent by a Visitor
        if ($message->sender_type === 'App\Models\Visitor' || $message->sender_type === 'visitor') {
            ProcessAiChatResponse::dispatch($message->chat_id, $message->body);
        }
    }
}
