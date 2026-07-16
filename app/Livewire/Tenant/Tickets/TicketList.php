<?php

namespace App\Livewire\Tenant\Tickets;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Ticket;
use Livewire\WithPagination;

#[Layout("layouts.tenant")]
class TicketList extends Component
{
    use WithPagination;

    public $search = "";

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tickets = Ticket::with(["user", "agent", "department", "status"])
            ->when($this->search, function ($query) {
                $query->where("subject", "like", "%" . $this->search . "%")
                      ->orWhere("description", "like", "%" . $this->search . "%");
            })
            ->latest()
            ->paginate(10);

        return view("livewire.tenant.tickets.ticket-list", [
            "tickets" => $tickets,
        ]);
    }
}
