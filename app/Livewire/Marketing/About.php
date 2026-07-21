<?php

namespace App\Livewire\Marketing;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('About Us | Zendesk')]
class About extends Component
{
    public function render()
    {
        return view('livewire.marketing.about');
    }
}
