<?php

namespace App\Services\Ticket;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\TicketToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTicketService
{
    public function execute(array $data, string $tenantId)
    {
        return DB::transaction(function () use ($data, $tenantId) {
            $customer = Customer::firstOrCreate(
                ['tenant_id' => $tenantId, 'email' => $data['email']],
                ['name' => $data['name'], 'phone' => $data['phone'] ?? null]
            );

            $ticket = Ticket::create([
                'tenant_id' => $tenantId,
                'customer_id' => $customer->id,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? 'low',
                'status_id' => 1, // Default to New/Open, handle properly later
            ]);

            $ticket->replies()->create([
                'customer_id' => $customer->id,
                'body' => $data['description'],
                'is_internal' => false,
            ]);

            // Generate Token
            $token = Str::random(64);
            TicketToken::create([
                'ticket_id' => $ticket->id,
                'token' => $token,
            ]);

            return [
                'ticket' => $ticket,
                'token' => $token,
            ];
        });
    }
}
