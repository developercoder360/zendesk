<?php

namespace App\Livewire\Marketing;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('Features | Zendesk')]
class Features extends Component
{
    public function render()
    {
        return view('livewire.marketing.features');
    }
}
