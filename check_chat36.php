<?php

use App\Models\Tenant;
use App\Models\Chat;

$tenant = Tenant::find('63c67449-5dee-48e7-b625-149cbbdb9926');
tenancy()->initialize($tenant);

$chat = Chat::find(36);
if ($chat) {
    echo "Chat 36 ID: {$chat->id}\n";
    echo "Messages Count: " . $chat->messages->count() . "\n";
    foreach ($chat->messages as $m) {
        $senderName = $m->is_ai_sender ? 'AI Assistant 🤖' : ($m->sender ? $m->sender->name : 'Visitor');
        echo "  - [{$m->created_at->format('H:i:s')}] {$senderName}: {$m->body}\n";
    }
} else {
    echo "Chat 36 not found (cleaned up or non-existent)\n";
}
