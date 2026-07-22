<?php

namespace App\Livewire\Tenant\Settings\Account;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class AccountIndex extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $position = '';

    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public $savedProfile = false;
    public $savedPassword = false;

    public function mount()
    {
        $user = auth()->user();
        $profile = $user->tenantProfile;

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $profile->phone ?? '';
        $this->position = $profile->position ?? '';
    }

    public function updateProfile()
    {
        $user = auth()->user();
        
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($user->tenantProfile) {
            $user->tenantProfile->update([
                'phone' => $this->phone,
                'position' => $this->position,
            ]);
        }

        $this->savedProfile = true;
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|string|current_password',
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->savedPassword = true;
    }

    public function render()
    {
        return view('livewire.tenant.settings.account.account-index');
    }
}
