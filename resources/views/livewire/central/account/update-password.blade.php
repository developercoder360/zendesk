<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('password-updated');
    }
}; ?>

<x-ui.card variant="sectioned" id="security" class="scroll-mt-24">
    <x-ui.card-header>
        <x-ui.card-title>Security</x-ui.card-title>
        <x-ui.card-description>Keep your account safe.</x-ui.card-description>
    </x-ui.card-header>
    
    <form wire:submit="updatePassword">
        <x-ui.card-content class="space-y-5">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2 sm:col-span-2">
                    <x-ui.label for="current_password">Current password</x-ui.label>
                    <x-ui.input wire:model="current_password" id="current_password" type="password" placeholder="••••••••" required />
                    @error('current_password') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid gap-2">
                    <x-ui.label for="password">New password</x-ui.label>
                    <x-ui.input wire:model="password" id="password" type="password" placeholder="••••••••" required />
                    @error('password') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid gap-2">
                    <x-ui.label for="password_confirmation">Confirm password</x-ui.label>
                    <x-ui.input wire:model="password_confirmation" id="password_confirmation" type="password" placeholder="••••••••" required />
                </div>
            </div>
        </x-ui.card-content>
        <x-ui.card-footer class="justify-end gap-2">
            <x-ui.button type="submit">
                <span wire:loading.remove wire:target="updatePassword">Update password</span>
                <span wire:loading wire:target="updatePassword">Updating...</span>
            </x-ui.button>
            
            <x-ui.badge variant="secondary" class="ml-2" x-data="{ shown: false, timeout: null }"
                x-on:password-updated.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);"
                x-show="shown" style="display: none;">
                Updated.
            </x-ui.badge>
        </x-ui.card-footer>
    </form>
</x-ui.card>
