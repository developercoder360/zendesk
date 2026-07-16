<?php

namespace App\Livewire\Tenant\Tickets;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Ticket;
use App\Models\TicketReply;

#[Layout("layouts.tenant")]
class TicketDetail extends Component
{
    public Ticket $ticket;
    public $replyBody = "";
    public $isInternal = false;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket->load(["user", "agent", "department", "status", "replies.user"]);
    }

    public function addReply()
    {
        $this->validate([
            "replyBody" => "required|string",
        ]);

        $this->ticket->replies()->create([
            "user_id" => auth()->id(),
            "body" => $this->replyBody,
            "is_internal" => $this->isInternal,
        ]);

        $this->reset("replyBody", "isInternal");
        $this->ticket->load("replies.user");
    }

    public function render()
    {
        return view("livewire.tenant.tickets.ticket-detail");
    }
}
