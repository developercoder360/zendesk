<?php

namespace App\Livewire\Legal;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('Cookie Policy | Zendesk')]
class Cookies extends Component
{
    public function render()
    {
        return view('livewire.legal.cookies');
    }
}
