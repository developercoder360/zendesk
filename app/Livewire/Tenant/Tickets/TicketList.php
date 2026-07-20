<?php

namespace App\Livewire\Tenant\Tickets;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class TicketList extends Component
{
    use WithPagination;

    public $search = '';

    public $filterStatus = '';

    public $filterAssignee = '';

    public $currentTab = 'all'; // all, my, unassigned

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterAssignee()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->currentTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $query = Ticket::with(['customer', 'user', 'agent', 'department', 'status']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status_id', $this->filterStatus);
        }

        if ($this->filterAssignee) {
            $query->where('agent_id', $this->filterAssignee);
        }

        if ($this->currentTab === 'my') {
            $query->where('agent_id', auth()->id());
        } elseif ($this->currentTab === 'unassigned') {
            $query->whereNull('agent_id');
        }

        $tickets = $query->latest()->paginate(10);
        $statuses = TicketStatus::all();
        $agents = User::all();

        return view('livewire.tenant.tickets.ticket-list', [
            'tickets' => $tickets,
            'statuses' => $statuses,
            'agents' => $agents,
        ]);
    }
}
