<?php

namespace App\Livewire\Central;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.central')]
class Account extends Component
{
    public function render()
    {
        return view('livewire.central.account');
    }
}
