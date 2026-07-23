<?php

use App\Models\Tenant;
use App\Models\Visitor;
use App\Models\Chat;
use App\Models\Message;
use App\Models\TenantUser;
use Livewire\Livewire;
use App\Livewire\Public\Widget\TicketForm;

$tenantId = '63c67449-5dee-48e7-b625-149cbbdb9926';
$tenant = Tenant::find($tenantId);
tenancy()->initialize($tenant);

// Set human agents offline
TenantUser::query()->update(['status' => 'offline']);

echo "=== TESTING AUTOMATIC QUEUE WORKER AI AUTO-REPLY ===\n\n";

$email = 'auto_queue_visitor@example.com';
$visitor = Visitor::where('email', $email)->first();
if ($visitor) {
    $chats = Chat::where('visitor_id', $visitor->id)->get();
    foreach ($chats as $c) {
        Message::where('chat_id', $c->id)->delete();
        $c->delete();
    }
    $v->delete();
}

$widgetSetting = App\Models\WidgetSetting::where('tenant_id', $tenantId)->first();
request()->query->set('key', $widgetSetting->embed_key);

// Submit form via Livewire component mount & submit
$lw = Livewire::test(TicketForm::class)
    ->set('name', 'Auto Queue Test Visitor')
    ->set('email', $email)
    ->set('subject', 'Pricing Question')
    ->set('description', 'How much does the Enterprise publishing package cost?')
    ->call('submit');

echo "Ticket Submitted. Checking database for Chat and queued Job...\n";

$visitor = Visitor::where('email', $email)->first();
$chat = Chat::where('visitor_id', $visitor->id)->latest()->first();

echo "Chat ID: {$chat->id}\n";
echo "Initial Messages Count: " . $chat->messages()->count() . "\n";

echo "Waiting 4 seconds for docker background laravel_queue worker to pick up and process job automatically...\n";
sleep(4);

$chat->refresh();
echo "Updated Messages Count: " . $chat->messages()->count() . "\n";
echo "Messages Transcript:\n";
foreach ($chat->messages as $m) {
    $senderName = $m->is_ai_sender ? 'AI Assistant 🤖' : ($m->sender ? $m->sender->name : 'Visitor');
    echo "  - [{$m->created_at->format('H:i:s')}] {$senderName}: {$m->body}\n";
}

if ($chat->messages()->count() > 1 && $chat->messages()->latest()->first()->is_ai_sender) {
    echo "\nSUCCESS: AI Auto-Reply was automatically processed by the background laravel_queue worker!\n";
} else {
    echo "\nFAIL: AI Auto-Reply was NOT processed automatically by queue worker.\n";
}

// Clean up test visitor
if ($visitor) {
    Message::where('chat_id', $chat->id)->delete();
    $chat->delete();
    $visitor->delete();
}
