<?php

namespace App\Livewire\Tenant\Tickets;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class TicketDetail extends Component
{
    public Ticket $ticket;

    public $replyBody = '';

    public $isInternal = false;

    public $status_id;

    public $priority;

    public $agent_id;

    public $department_id;

    public $agents = [];

    public $statuses = [];

    public $departments = [];

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket->load(['customer', 'user', 'agent', 'department', 'status', 'replies.user', 'replies.customer']);

        $this->status_id = $this->ticket->status_id;
        $this->priority = $this->ticket->priority;
        $this->agent_id = $this->ticket->agent_id;
        $this->department_id = $this->ticket->department_id;

        $this->agents = User::all();
        $this->statuses = TicketStatus::all();
        $this->departments = Department::all();
    }

    public function updatedStatusId()
    {
        $this->ticket->update(['status_id' => $this->status_id ?: null]);
        $this->ticket->load('status');
    }

    public function updatedPriority()
    {
        $this->ticket->update(['priority' => $this->priority]);
    }

    public function updatedAgentId()
    {
        $this->ticket->update(['agent_id' => $this->agent_id ?: null]);
        $this->ticket->load('agent');
    }

    public function updatedDepartmentId()
    {
        $this->ticket->update(['department_id' => $this->department_id ?: null]);
        $this->ticket->load('department');
    }

    public function addReply()
    {
        $this->validate([
            'replyBody' => 'required|string',
        ]);

        $this->ticket->replies()->create([
            'user_id' => auth()->id(),
            'body' => $this->replyBody,
            'is_internal' => $this->isInternal,
        ]);

        $this->reset('replyBody', 'isInternal');
        $this->ticket->load('replies.user');
    }

    public function render()
    {
        return view('livewire.tenant.tickets.ticket-detail');
    }
}
