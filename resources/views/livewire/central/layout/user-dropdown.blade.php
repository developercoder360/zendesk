<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<x-ui.dropdown-menu>
    <x-ui.dropdown-menu-trigger>
        <button aria-label="Account">
            <x-ui.avatar class="size-8">
                <x-ui.avatar-fallback>{{ substr(auth()->user()->name ?? 'U', 0, 2) }}</x-ui.avatar-fallback>
            </x-ui.avatar>
        </button>
    </x-ui.dropdown-menu-trigger>
    <x-ui.dropdown-menu-content align="end" class="w-48">
        <x-ui.dropdown-menu-label>
            <div class="flex flex-col space-y-1">
                <p class="text-sm font-medium leading-none">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-xs leading-none text-muted-foreground">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </x-ui.dropdown-menu-label>
        <x-ui.dropdown-menu-separator />
        <x-ui.dropdown-menu-item wire:click="redirect('{{ route('central.account') }}', true)">
            <x-lucide-user class="mr-2 size-4" />
            <span>Profile</span>
        </x-ui.dropdown-menu-item>
        <x-ui.dropdown-menu-item wire:click="redirect('{{ route('central.billing') ?? '#' }}', true)">
            <x-lucide-credit-card class="mr-2 size-4" />
            <span>Billing</span>
        </x-ui.dropdown-menu-item>
        <x-ui.dropdown-menu-separator />
        <x-ui.dropdown-menu-item wire:click="logout">
            <x-lucide-log-out class="mr-2 size-4" />
            <span>Log out</span>
        </x-ui.dropdown-menu-item>
    </x-ui.dropdown-menu-content>
</x-ui.dropdown-menu>
