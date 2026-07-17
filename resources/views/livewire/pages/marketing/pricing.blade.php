<div>
<x-slot:meta_description>Simple, transparent pricing for teams of all sizes. Find the right Zendesk package for your business.</x-slot:meta_description>

    <section class="py-24 lg:py-32">
        <div class="container mx-auto px-4 md:px-6 max-w-7xl">
            <div class="text-center mb-16">
                <x-ui.badge variant="soft" tone="info" class="mb-4">Pricing</x-ui.badge>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6">Simple, transparent pricing</h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto mb-10">No hidden fees. No surprise charges. Choose the package that best fits your needs.</p>
                
                <!-- Billing Toggle using BlatUI Switch -->
                <div class="flex items-center justify-center space-x-4">
                    <x-ui.label for="billing-toggle" class="text-sm font-medium" :class="!$annual ? 'text-foreground' : 'text-muted-foreground'">Monthly</x-ui.label>
                    <x-ui.switch id="billing-toggle" wire:model.live="annual" />
                    <x-ui.label for="billing-toggle" class="text-sm font-medium flex items-center gap-1.5" :class="$annual ? 'text-foreground' : 'text-muted-foreground'">
                        Annually
                        <x-ui.badge variant="soft" tone="success" size="sm">Save 20%</x-ui.badge>
                    </x-ui.label>
                </div>
            </div>

            <!-- Pricing Cards Grid -->
            <div class="grid lg:grid-cols-3 gap-8 max-w-6xl mx-auto mb-24">
                @foreach($packages as $name => $variants)
                    @php 
                        $package = $this->priceFor($variants); 
                        if (!$package) continue;
                    @endphp
                    <x-ui.card class="flex flex-col {{ $package->is_popular ? 'border-primary shadow-lg relative scale-105 z-10' : 'bg-card/50' }}">
                        @if($package->is_popular)
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2">
                                <x-ui.badge variant="default" class="uppercase tracking-wider font-bold">Most Popular</x-ui.badge>
                            </div>
                        @endif
                        
                        <x-ui.card-header>
                            <x-ui.card-title class="text-2xl">{{ $name }}</x-ui.card-title>
                            <x-ui.card-description>Everything you need for {{ strtolower($name) }} scale.</x-ui.card-description>
                        </x-ui.card-header>
                        <x-ui.card-content class="flex-1">
                            <div class="mb-6">
                                <span class="text-4xl font-extrabold">${{ number_format($package->price) }}</span>
                                <span class="text-muted-foreground">/mo</span>
                            </div>
                            <ul class="space-y-3 mb-8">
                                @foreach($this->featuresFor($package) as $feature)
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-primary shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <span class="text-sm text-muted-foreground">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </x-ui.card-content>
                        <x-ui.card-footer>
                            @auth
                                <a href="{{ route('central.dashboard') }}" class="w-full">
                                    <x-ui.button class="w-full" variant="{{ $package->is_popular ? 'default' : 'outline' }}">Current Package</x-ui.button>
                                </a>
                            @else
                                <a href="/register" class="w-full">
                                    <x-ui.button class="w-full" variant="{{ $package->is_popular ? 'default' : 'outline' }}">Get Started</x-ui.button>
                                </a>
                            @endauth
                        </x-ui.card-footer>
                    </x-ui.card>
                @endforeach
            </div>

            <!-- Feature Comparison Table -->
            <div class="max-w-5xl mx-auto mb-24">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold tracking-tight mb-4">Compare Features</h2>
                    <p class="text-muted-foreground">Detailed breakdown of what's included in each plan.</p>
                </div>
                
                <x-ui.table variant="card">
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head class="w-[300px]">Features</x-ui.table-head>
                            @foreach($packages as $name => $variants)
                                <x-ui.table-head class="text-center">{{ $name }}</x-ui.table-head>
                            @endforeach
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @foreach($this->comparisonFeatures() as $feature)
                            <x-ui.table-row>
                                <x-ui.table-cell class="font-medium text-muted-foreground">{{ $feature }}</x-ui.table-cell>
                                @foreach($packages as $name => $variants)
                                    @php 
                                        $package = $this->priceFor($variants); 
                                        $formattedFlags = $package ? $this->formatFeatureFlags($package->feature_flags) : [];
                                        $hasFeature = in_array($feature, $formattedFlags);
                                    @endphp
                                    <x-ui.table-cell class="text-center">
                                        @if($hasFeature)
                                            <svg class="w-5 h-5 text-primary mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        @else
                                            <svg class="w-5 h-5 text-muted-foreground/30 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        @endif
                                    </x-ui.table-cell>
                                @endforeach
                            </x-ui.table-row>
                        @endforeach
                    </x-ui.table-body>
                </x-ui.table>
            </div>

            <!-- FAQ Accordion -->
            <div class="max-w-3xl mx-auto mb-24">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold tracking-tight mb-4">Frequently Asked Questions</h2>
                    <p class="text-muted-foreground">Everything you need to know about the product and billing.</p>
                </div>

                <x-ui.accordion>
                    <x-ui.accordion-item value="item-1">
                        <x-ui.accordion-trigger>Can I switch plans later?</x-ui.accordion-trigger>
                        <x-ui.accordion-content>
                            Absolutely. You can upgrade or downgrade your plan at any time. Prorated charges or credits will automatically be applied to your account.
                        </x-ui.accordion-content>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item value="item-2">
                        <x-ui.accordion-trigger>What payment methods do you accept?</x-ui.accordion-trigger>
                        <x-ui.accordion-content>
                            We accept all major credit cards including Visa, Mastercard, American Express, and Discover. For enterprise customers, we also support invoicing.
                        </x-ui.accordion-content>
                    </x-ui.accordion-item>
                    <x-ui.accordion-item value="item-3">
                        <x-ui.accordion-trigger>Do you offer a free trial?</x-ui.accordion-trigger>
                        <x-ui.accordion-content>
                            Yes! All plans come with a 14-day free trial. No credit card is required to start your trial.
                        </x-ui.accordion-content>
                    </x-ui.accordion-item>
                </x-ui.accordion>
            </div>

            <!-- CTA Banner -->
            <div class="bg-primary/5 rounded-3xl p-8 md:p-16 text-center border border-primary/10">
                <h2 class="text-3xl font-bold tracking-tight mb-4">Ready to get started?</h2>
                <p class="text-muted-foreground text-lg mb-8 max-w-2xl mx-auto">Join thousands of teams already using our platform to scale their customer support operations.</p>
                @auth
                    <a href="{{ route('central.dashboard') }}">
                        <x-ui.button size="lg" class="px-8">Go to Dashboard</x-ui.button>
                    </a>
                @else
                    <a href="/register">
                        <x-ui.button size="lg" class="px-8">Start your free trial</x-ui.button>
                    </a>
                @endauth
            </div>

        </div>
    </section>
</div>
