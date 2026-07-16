<?php

namespace App\Livewire\Public\Ticket;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\TicketToken;

#[Layout("layouts.guest")]
class ViewTicket extends Component
{
    public $ticket;
    public $tokenRecord;
    public $replyBody = "";

    public function mount($token)
    {
        $this->tokenRecord = TicketToken::with("ticket.replies.user", "ticket.replies.customer")
            ->where("token", $token)
            ->firstOrFail();

        // Check if token expired (if you implemented expires_at logic)
        if ($this->tokenRecord->expires_at && $this->tokenRecord->expires_at->isPast()) {
            abort(403, "This ticket link has expired.");
        }

        $this->ticket = $this->tokenRecord->ticket;
    }

    public function addReply()
    {
        $this->validate([
            "replyBody" => "required|string",
        ]);

        $this->ticket->replies()->create([
            "customer_id" => $this->ticket->customer_id,
            "body" => $this->replyBody,
            "is_internal" => false,
        ]);

        $this->reset("replyBody");
        $this->ticket->refresh();
        $this->ticket->load("replies.user", "replies.customer");
    }

    public function render()
    {
        return view("livewire.public.ticket.view-ticket");
    }
}
