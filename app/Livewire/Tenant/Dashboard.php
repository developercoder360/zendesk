<?php

namespace App\Livewire\Tenant;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.tenant.dashboard');
    }
}
