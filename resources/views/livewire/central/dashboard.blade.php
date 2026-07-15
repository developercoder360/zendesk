<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.central')] class extends Component {
    public $user;
    public $domain;
    
    public function mount()
    {
        $this->user = auth()->user();
        $this->domain = \App\Models\Domain::where('tenant_id', $this->user->tenant_id)->first();
    }

    public function launchWorkspace()
    {
        if (! $this->domain) {
            return;
        }

        $token = \Illuminate\Support\Str::random(64);
        cache()->put('tenant_login_' . $token, [
            'user_id' => $this->user->id,
            'redirect' => '/dashboard',
        ], now()->addMinutes(5));

        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $this->domain->domain . '/tenant-login?token=' . $token);
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
            <p class="text-muted-foreground mt-1 text-sm">Welcome back, {{ $user->name }}. Here is an overview of your workspaces.</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Total Workspaces</x-ui.card-title>
                <x-lucide-box class="text-muted-foreground size-4" />
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold">1</div>
                <p class="text-muted-foreground text-xs">Active environments</p>
            </x-ui.card-content>
        </x-ui.card>
        
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title class="text-sm font-medium">Subscription</x-ui.card-title>
                <x-lucide-credit-card class="text-muted-foreground size-4" />
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="text-2xl font-bold">Active</div>
                <p class="text-muted-foreground text-xs">Next billing cycle in 14 days</p>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <h2 class="mt-8 text-xl font-semibold tracking-tight">Your Workspaces</h2>
    
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @if ($domain)
        <x-ui.card class="flex flex-col">
            <x-ui.card-header>
                <div class="flex items-center justify-between">
                    <x-ui.card-title>Primary Workspace</x-ui.card-title>
                    <x-ui.badge variant="secondary">Active</x-ui.badge>
                </div>
                <x-ui.card-description>{{ $domain->domain }}</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="flex-1">
                <div class="text-sm text-muted-foreground">
                    This is your main application environment. You have full administrative access.
                </div>
            </x-ui.card-content>
            <x-ui.card-footer>
                <x-ui.button class="w-full" wire:click="launchWorkspace">
                    Launch Workspace <x-lucide-arrow-right class="ml-2 size-4" />
                </x-ui.button>
            </x-ui.card-footer>
        </x-ui.card>
        @else
        <x-ui.card class="flex flex-col">
            <x-ui.card-header>
                <x-ui.card-title>No Workspace Found</x-ui.card-title>
                <x-ui.card-description>You don't have an active workspace yet.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="flex-1">
                <div class="text-sm text-muted-foreground">
                    Please contact support to provision your workspace.
                </div>
            </x-ui.card-content>
        </x-ui.card>
        @endif
        
        <x-ui.card class="flex flex-col border-dashed shadow-none">
            <x-ui.card-header>
                <x-ui.card-title>Add Workspace</x-ui.card-title>
                <x-ui.card-description>Create a new isolated environment</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="flex flex-1 items-center justify-center py-6">
                <div class="rounded-full bg-muted p-3">
                    <x-lucide-plus class="size-6 text-muted-foreground" />
                </div>
            </x-ui.card-content>
            <x-ui.card-footer>
                <x-ui.button variant="outline" class="w-full">Create New</x-ui.button>
            </x-ui.card-footer>
        </x-ui.card>
    </div>
</div>
