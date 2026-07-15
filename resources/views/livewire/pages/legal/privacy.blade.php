<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('layouts.marketing')]
#[Title('Privacy Policy | Zendesk')]
class extends Component {
    //
}; ?>

<div>
    <section class="py-24 lg:py-32">
        <div class="container mx-auto px-4 md:px-6 max-w-3xl prose dark:prose-invert">
            <h1>Privacy Policy</h1>
            <p>Last updated: {{ date('F j, Y') }}</p>
            <p>At Zendesk, we take your privacy seriously. This Privacy Policy describes how we collect, use, and share your personal data when you use our services.</p>
            <h2>Information we collect</h2>
            <p>We collect information you provide directly to us, such as when you create or modify your account, request support, or otherwise communicate with us.</p>
            <h2>How we use information</h2>
            <p>We use the information we collect to provide, maintain, and improve our services, and to process transactions.</p>
        </div>
    </section>
</div>
