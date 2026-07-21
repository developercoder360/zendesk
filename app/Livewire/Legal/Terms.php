<?php

namespace App\Livewire\Legal;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('Terms of Service | Zendesk')]
class Terms extends Component
{
    public function render()
    {
        return view('livewire.legal.terms');
    }
}
