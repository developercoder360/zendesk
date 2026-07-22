<?php

namespace App\Livewire\Tenant\Tickets;

use App\Models\Chat;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class TicketHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $filterAssignee = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterAssignee()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Chat::with(['visitor', 'agent', 'department'])
            ->whereIn('status', ['resolved', 'closed', 'Resolved', 'Closed']);

        if ($this->search) {
            $query->whereHas('visitor', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterAssignee) {
            $query->where('assigned_agent_id', $this->filterAssignee);
        }

        $tickets = $query->latest('updated_at')->paginate(10);
        $agents = User::all();

        return view('livewire.tenant.tickets.ticket-history', [
            'tickets' => $tickets,
            'agents' => $agents,
        ]);
    }
}
