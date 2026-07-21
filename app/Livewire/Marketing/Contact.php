<?php

namespace App\Livewire\Marketing;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('Contact Sales | Zendesk')]
class Contact extends Component
{
    public string $name = '';
    public string $email = '';
    public string $company = '';
    public string $message = '';
    public bool $sent = false;

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'company' => 'required',
            'message' => 'required',
        ]);

        // Logic to send email or save lead
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.marketing.contact');
    }
}
