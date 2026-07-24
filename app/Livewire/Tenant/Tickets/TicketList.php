<?php

namespace App\Livewire\Tenant\Tickets;

use App\Models\Chat;
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
        $query = Chat::with(['visitor', 'agent', 'department']);

        if ($this->search) {
            $query->whereHas('visitor', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterAssignee) {
            $query->where('assigned_agent_id', $this->filterAssignee);
        }

        if ($this->currentTab === 'my') {
            $query->where('assigned_agent_id', auth()->user()->tenantProfile?->id);
        } elseif ($this->currentTab === 'unassigned') {
            $query->whereNull('assigned_agent_id');
        }

        $tickets = $query->latest()->paginate(10);
        $statuses = ['open', 'resolved', 'closed'];
        $agents = \App\Models\TenantUser::where('tenant_id', tenant('id'))
            ->whereHas('user', function ($q) {
                $q->where('tenant_id', tenant('id'));
            })
            ->where(function ($q) {
                $q->where('is_ai', false)->orWhereNull('is_ai');
            })
            ->with('user')
            ->get();

        return view('livewire.tenant.tickets.ticket-list', [
            'tickets' => $tickets,
            'statuses' => collect($statuses)->map(fn($s) => (object)['id' => $s, 'name' => ucfirst($s)]),
            'agents' => $agents,
        ]);
    }
}
