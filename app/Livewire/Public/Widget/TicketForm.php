<?php

namespace App\Livewire\Public\Widget;

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

        $visitor = \App\Models\Visitor::firstOrCreate(
            ['email' => $this->email],
            ['name' => $this->name, 'session_id' => \Illuminate\Support\Str::uuid()->toString(), 'ip_address' => request()->ip()]
        );

        $chat = \App\Models\Chat::create([
            'tenant_id' => tenant('id'),
            'visitor_id' => $visitor->id,
            'status' => 'open',
        ]);

        $chat->messages()->create([
            'sender_id' => $visitor->id,
            'sender_type' => \App\Models\Visitor::class,
            'body' => "Subject: " . $this->subject . "\n\n" . $this->description,
        ]);

        $this->reset(['name', 'email', 'subject', 'description']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.public.widget.ticket-form');
    }
}
