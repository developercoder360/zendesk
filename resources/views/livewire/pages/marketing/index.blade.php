<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('layouts.marketing')]
#[Title('Customer Service Software & Sales CRM | Zendesk')]
class extends Component {
    //
}; ?>

<div>
<x-slot:meta_description>Zendesk is a customer service platform that builds software to meet customer needs, set your team up for success, and keep your business in sync.</x-slot:meta_description>

    <!-- Hero Section -->
    <section class="py-24 lg:py-32 overflow-hidden">
        <div class="container mx-auto px-4 md:px-6 max-w-7xl text-center">
            <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 mb-6">
                <span>New AI capabilities announced &rarr;</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-foreground mb-6">
                Champions of customer service
            </h1>
            <p class="text-lg md:text-xl text-muted-foreground mb-10 max-w-2xl mx-auto leading-relaxed">
                Build better customer experiences with the platform that puts relationships first. Fast to set up, easy to use, and scales with your business.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}">
                    <x-ui.button size="lg" class="w-full sm:w-auto h-12 px-8 text-base font-medium">Start free trial</x-ui.button>
                </a>
                <a href="{{ route('pricing') }}">
                    <x-ui.button variant="outline" size="lg" class="w-full sm:w-auto h-12 px-8 text-base font-medium">View pricing</x-ui.button>
                </a>
            </div>

            <div class="mt-20 mx-auto max-w-5xl rounded-xl border bg-background/50 p-2 shadow-2xl backdrop-blur">
                <div class="rounded-lg border bg-muted/40 aspect-video overflow-hidden flex items-center justify-center">
                    <!-- Placeholder for dashboard mockup -->
                    <x-lucide-layout-dashboard class="w-16 h-16 text-muted-foreground/30" />
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted By -->
    <section class="py-12 border-y bg-muted/20">
        <div class="container mx-auto px-4 md:px-6 max-w-7xl text-center">
            <p class="text-sm font-medium text-muted-foreground mb-8">TRUSTED BY INNOVATIVE TEAMS WORLDWIDE</p>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-300">
                <!-- Using lucide icons as placeholders for logos -->
                <x-lucide-twitch class="h-8 w-auto" />
                <x-lucide-trello class="h-8 w-auto" />
                <x-lucide-slack class="h-8 w-auto" />
                <x-lucide-github class="h-8 w-auto" />
                <x-lucide-figma class="h-8 w-auto" />
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-24 lg:py-32">
        <div class="container mx-auto px-4 md:px-6 max-w-7xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-4">Everything you need to deliver</h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">Zendesk gives your team the best tools to manage support tickets, engage customers, and analyze performance.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <x-ui.card class="border-border/50 bg-background/50 hover:bg-background transition-colors">
                    <x-ui.card-header>
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <x-lucide-message-square class="w-5 h-5 text-primary" />
                        </div>
                        <x-ui.card-title>Omnichannel Support</x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <p class="text-muted-foreground">Meet your customers wherever they are: email, chat, phone, or social messaging. All in one unified workspace.</p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border-border/50 bg-background/50 hover:bg-background transition-colors">
                    <x-ui.card-header>
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <x-lucide-zap class="w-5 h-5 text-primary" />
                        </div>
                        <x-ui.card-title>AI & Automation</x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <p class="text-muted-foreground">Resolve issues faster with intelligent routing, automated responses, and AI-powered knowledge management.</p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card class="border-border/50 bg-background/50 hover:bg-background transition-colors">
                    <x-ui.card-header>
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <x-lucide-bar-chart-3 class="w-5 h-5 text-primary" />
                        </div>
                        <x-ui.card-title>Advanced Analytics</x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <p class="text-muted-foreground">Make data-driven decisions with real-time dashboards and comprehensive reporting on team performance.</p>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-24 bg-muted/30">
        <div class="container mx-auto px-4 md:px-6 max-w-3xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold tracking-tight mb-4">Frequently asked questions</h2>
            </div>

            <x-ui.accordion>
                <x-ui.accordion-item value="item-1">
                    <x-ui.accordion-trigger>How long does it take to implement Zendesk?</x-ui.accordion-trigger>
                    <x-ui.accordion-content>
                        Most of our customers are up and running within a few days. Our platform is designed to be intuitive and easy to set up without requiring extensive IT support.
                    </x-ui.accordion-content>
                </x-ui.accordion-item>

                <x-ui.accordion-item value="item-2">
                    <x-ui.accordion-trigger>Can I integrate Zendesk with my existing tools?</x-ui.accordion-trigger>
                    <x-ui.accordion-content>
                        Yes! Zendesk integrates with over 1,000 apps in our marketplace, including Salesforce, Slack, Jira, and many more. We also offer robust APIs for custom integrations.
                    </x-ui.accordion-content>
                </x-ui.accordion-item>

                <x-ui.accordion-item value="item-3">
                    <x-ui.accordion-trigger>Do you offer support during the trial?</x-ui.accordion-trigger>
                    <x-ui.accordion-content>
                        Absolutely. You have full access to our help center, community forums, and our customer support team during your 14-day free trial.
                    </x-ui.accordion-content>
                </x-ui.accordion-item>
            </x-ui.accordion>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 lg:py-32 border-t">
        <div class="container mx-auto px-4 md:px-6 max-w-4xl text-center">
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-6">Ready to champion customer service?</h2>
            <p class="text-xl text-muted-foreground mb-10">Join over 100,000 brands delivering exceptional customer experiences with Zendesk.</p>
            <a href="{{ route('register') }}">
                <x-ui.button size="lg" class="h-14 px-8 text-lg font-medium">Start your free trial</x-ui.button>
            </a>
            <p class="mt-4 text-sm text-muted-foreground">No credit card required. 14-day free trial.</p>
        </div>
    </section>
</div>
