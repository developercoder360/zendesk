<?php

namespace App\Livewire\Central;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.central')]
class Billing extends Component
{
    public function render()
    {
        return view('livewire.central.billing');
    }
}
