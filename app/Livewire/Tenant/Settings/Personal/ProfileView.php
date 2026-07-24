<?php

namespace App\Livewire\Tenant\Settings\Personal;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant')]
#[Title('Personal Settings')]
class ProfileView extends Component
{
    // Profile Fields
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $position = '';

    // Password Fields
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Status Signals
    public bool $savedProfile = false;
    public bool $savedPassword = false;

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
        $this->dispatch('toast', message: 'Profile information updated successfully.', type: 'success');
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
        $this->dispatch('toast', message: 'Password updated successfully.', type: 'success');
    }

    public function render()
    {
        return view('livewire.tenant.settings.personal.profile-view');
    }
}
