<?php

namespace App\Livewire\Tenant\Tickets;

use App\Models\Department;
use App\Models\Chat;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class TicketDetail extends Component
{
    public Chat $ticket;

    public $replyBody = '';

    public $status;

    public $assigned_agent_id;

    public $department_id;

    public $agents = [];

    public $statuses = [];

    public $departments = [];

    public function mount(Chat $ticket)
    {
        $this->ticket = $ticket->load(['visitor', 'agent.user', 'department', 'messages.sender']);

        $this->status = $this->ticket->status;
        $this->assigned_agent_id = $this->ticket->assigned_agent_id;
        $this->department_id = $this->ticket->department_id;

        $this->agents = \App\Models\TenantUser::with('user')->get();
        $this->statuses = collect(['open', 'resolved', 'closed'])->map(fn($s) => (object)['id' => $s, 'name' => ucfirst($s)]);
        $this->departments = Department::all();
    }

    public function updatedStatus()
    {
        $this->ticket->update(['status' => $this->status ?: 'open']);
    }

    public function updatedAssignedAgentId()
    {
        $this->ticket->update(['assigned_agent_id' => $this->assigned_agent_id ?: null]);
        $this->ticket->load('agent.user');
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

        $this->ticket->messages()->create([
            'sender_id' => auth()->user()->tenantProfile->id,
            'sender_type' => \App\Models\TenantUser::class,
            'body' => $this->replyBody,
        ]);

        $this->reset('replyBody');
        $this->ticket->load('messages.sender');
    }

    public function render()
    {
        return view('livewire.tenant.tickets.ticket-detail');
    }
}
