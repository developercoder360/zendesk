<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.central')] class extends Component {
    //
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Billing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>{{ __('Billing Overview') }}</x-ui.card-title>
                    <x-ui.card-description>{{ __('Manage your subscription and payment methods.') }}</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="text-sm text-muted-foreground">
                        {{ __('Billing integration is coming soon.') }}
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>
</div>
