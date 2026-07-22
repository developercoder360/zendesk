<?php

namespace App\Livewire\Tenant\Settings\Personal;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class ProfileView extends Component
{
    public function render()
    {
        return view('livewire.tenant.settings.personal.profile-view');
    }
}
