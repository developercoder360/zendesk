<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('layouts.marketing')]
#[Title('About Us | Zendesk')]
class extends Component {
    //
}; ?>

<div>
    <section class="py-24 lg:py-32">
        <div class="container mx-auto px-4 md:px-6 max-w-4xl text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6">We are champions of customer service</h1>
            <p class="text-xl text-muted-foreground mb-16">Zendesk was started with a simple idea: make customer service software that's easy to use and accessible to everyone.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold">Our Mission</h2>
                    <p class="text-muted-foreground">To simplify the complexity of business and make it easier for companies and customers to create connections.</p>
                </div>
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold">Our Vision</h2>
                    <p class="text-muted-foreground">A world where every customer interaction is smooth, personal, and profoundly simple.</p>
                </div>
            </div>
        </div>
    </section>
</div>
