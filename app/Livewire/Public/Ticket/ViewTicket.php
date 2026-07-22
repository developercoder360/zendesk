<?php

namespace App\Livewire\Public\Ticket;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class ViewTicket extends Component
{
    public $ticket;

    public $replyBody = '';

    public function mount($token)
    {
        // For now, treat the token as the Chat ID since TicketToken model was removed
        $this->ticket = \App\Models\Chat::with('messages.sender')
            ->where('id', $token)
            ->firstOrFail();
    }

    public function addReply()
    {
        $this->validate([
            'replyBody' => 'required|string',
        ]);

        $this->ticket->messages()->create([
            'sender_id' => $this->ticket->visitor_id,
            'sender_type' => \App\Models\Visitor::class,
            'body' => $this->replyBody,
        ]);

        $this->reset('replyBody');
        $this->ticket->load('messages.sender');
    }

    public function render()
    {
        return view('livewire.public.ticket.view-ticket');
    }
}
