<?php

namespace App\Livewire\Marketing;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('Customer Service Software & Sales CRM | Zendesk')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.marketing.index');
    }
}
