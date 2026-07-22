<?php

namespace App\Services\Ticket;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTicketService
{
    public function execute(array $data, string $tenantId)
    {
        return DB::transaction(function () use ($data, $tenantId) {
            $visitor = \App\Models\Visitor::firstOrCreate(
                ['tenant_id' => $tenantId, 'email' => $data['email']],
                ['name' => $data['name'], 'phone' => $data['phone'] ?? null, 'session_id' => Str::uuid()->toString(), 'ip_address' => request()->ip()]
            );

            $chat = \App\Models\Chat::create([
                'tenant_id' => $tenantId,
                'visitor_id' => $visitor->id,
                'status' => 'open',
            ]);

            $chat->messages()->create([
                'sender_id' => $visitor->id,
                'sender_type' => \App\Models\Visitor::class,
                'body' => "Subject: " . $data['subject'] . "\n\n" . $data['description'],
            ]);

            return [
                'ticket' => $chat,
                'token' => $chat->id, // Fallback since TicketToken is removed
            ];
        });
    }
}
