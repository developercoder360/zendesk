<?php

namespace App\Livewire\Public\Widget;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.widget')]
class TicketForm extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|string|max:255')]
    public $subject = '';

    #[Validate('required|string')]
    public $description = '';

    public $success = false;

    public function submit()
    {
        $this->validate();

        // Check if customer exists in current tenant
        $customer = Customer::firstOrCreate(
            ['email' => $this->email],
            ['name' => $this->name]
        );

        // Get default Open status
        $status = DB::table('ticket_statuses')->where('name', 'Open')->first();
        $statusId = $status ? $status->id : 1;

        // Create ticket
        Ticket::create([
            'tenant_id' => tenant('id'),
            'customer_id' => $customer->id,
            'subject' => $this->subject,
            'description' => $this->description,
            'status_id' => $statusId,
            'priority' => 'normal',
        ]);

        $this->reset(['name', 'email', 'subject', 'description']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.public.widget.ticket-form');
    }
}
