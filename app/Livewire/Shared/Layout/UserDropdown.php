<?php

namespace App\Livewire\Shared\Layout;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class UserDropdown extends Component
{
    public function goTo(string $url): void
    {
        $this->redirect($url, navigate: true);
    }

    public function logout(Logout $logout)
    {
        $logout();
        
        $centralUrl = rtrim(config('app.url'), '/');
        return redirect()->away($centralUrl . '/login');
    }

    public function render()
    {
        return view('livewire.shared.layout.user-dropdown');
    }
}
