<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Plan;
use Illuminate\Support\Collection;

new
#[Layout('layouts.marketing')]
#[Title('Pricing | Zendesk')]
class extends Component {
    public bool $annual = false;
    public bool $supportsYearly = false;
    public Collection $plans;

    public function mount()
    {
        $this->plans = Plan::where('is_active', true)->get();
    }

    public function toggleBilling()
    {
        if (! $this->supportsYearly && ! $this->annual) {
            $this->dispatch('open-alert-dialog-yearly');
            return;
        }

        $this->annual = !$this->annual;
    }
}; ?>

<div>
<x-slot:meta_description>Simple, transparent pricing for teams of all sizes. Find the right Zendesk plan for your business.</x-slot:meta_description>

    <section class="py-24 lg:py-32">
        <div class="container mx-auto px-4 md:px-6 max-w-7xl">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6">Simple, transparent pricing</h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto mb-10">No hidden fees. No surprise charges. Choose the plan that best fits your needs.</p>
                
                <!-- Billing Toggle -->
                <div class="flex items-center justify-center space-x-4">
                    <span class="text-sm font-medium" :class="!$wire.annual ? 'text-foreground' : 'text-muted-foreground'">Monthly</span>
                    <button wire:click="toggleBilling" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center justify-center rounded-full border-2 border-transparent bg-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background">
                        <span class="sr-only">Toggle billing cycle</span>
                        <span aria-hidden="true" class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform" :class="$wire.annual ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                    <span class="text-sm font-medium flex items-center gap-1.5" :class="$wire.annual ? 'text-foreground' : 'text-muted-foreground'">
                        Annually
                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold transition-colors bg-primary/10 text-primary border-transparent">Save 20%</span>
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($plans as $plan)
                    <x-ui.card class="border-border/50 flex flex-col {{ $plan->is_popular ? 'border-primary shadow-md relative scale-105 z-10 bg-background' : 'bg-background/50' }}">
                        @if($plan->is_popular)
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2">
                            <span class="bg-primary text-primary-foreground text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Most Popular</span>
                        </div>
                        @endif
                        
                        <x-ui.card-header>
                            <x-ui.card-title class="text-xl">{{ $plan->name }}</x-ui.card-title>
                            <x-ui.card-description>{{ $plan->description }}</x-ui.card-description>
                        </x-ui.card-header>
                        <x-ui.card-content class="flex-1">
                            <div class="mb-6">
                                <span class="text-4xl font-extrabold" x-text="$wire.annual ? '${{ $plan->price_yearly }}' : '${{ $plan->price }}'">${{ $plan->price }}</span>
                                <span class="text-muted-foreground">/agent/mo</span>
                            </div>
                            <ul class="space-y-3 mb-8">
                                @foreach($plan->features ?? [] as $feature)
                                <li class="flex items-center gap-2">
                                    <x-lucide-check class="w-4 h-4 text-primary" />
                                    <span class="text-sm {{ $loop->first && str_contains($feature, 'Everything in') ? 'font-medium' : '' }}">{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </x-ui.card-content>
                        <x-ui.card-footer>
                            @auth
                                <a href="/dashboard" class="w-full">
                                    <x-ui.button class="w-full" variant="{{ $plan->is_popular ? 'default' : 'outline' }}">Current Plan</x-ui.button>
                                </a>
                            @else
                                <a href="{{ $plan->cta_link ?? '/register' }}" class="w-full">
                                    <x-ui.button class="w-full" variant="{{ $plan->is_popular ? 'default' : 'outline' }}">{{ $plan->cta_text ?? 'Get Started' }}</x-ui.button>
                                </a>
                            @endauth
                        </x-ui.card-footer>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Yearly Billing Alert Modal -->
    <x-ui.alert-dialog id="yearly">
        <x-ui.alert-dialog-content>
            <x-ui.alert-dialog-header>
                <x-ui.alert-dialog-title>Coming Soon</x-ui.alert-dialog-title>
                <x-ui.alert-dialog-description>
                    Yearly billing is not available yet. This feature is currently under development and will be released in a future update.
                </x-ui.alert-dialog-description>
            </x-ui.alert-dialog-header>
            <x-ui.alert-dialog-footer>
                <x-ui.alert-dialog-cancel>OK</x-ui.alert-dialog-cancel>
            </x-ui.alert-dialog-footer>
        </x-ui.alert-dialog-content>
    </x-ui.alert-dialog>
</div>
