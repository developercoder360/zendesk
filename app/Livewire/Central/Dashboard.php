<?php

namespace App\Livewire\Central;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use App\Models\Domain;

#[Layout('layouts.central')]
class Dashboard extends Component
{
    public $user;
    public $domain;

    public function mount()
    {
        $this->user = auth()->user();
        $this->domain = Domain::where('tenant_id', $this->user->tenant_id)->first();
    }

    public function launchWorkspace()
    {
        if (! $this->domain) {
            return;
        }

        $token = Str::random(64);
        cache()->put('tenant_login_' . $token, [
            'user_id' => $this->user->id,
            'redirect' => '/dashboard',
        ], now()->addMinutes(5));

        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $this->domain->domain . '/tenant-login?token=' . $token);
    }

    public function render()
    {
        return view('livewire.central.dashboard');
    }
}
