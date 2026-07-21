<?php

namespace App\Livewire\Legal;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('Privacy Policy | Zendesk')]
class Privacy extends Component
{
    public function render()
    {
        return view('livewire.legal.privacy');
    }
}
