<div>
    <section class="py-24 lg:py-32">
        <div class="container mx-auto px-4 md:px-6 max-w-2xl">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight mb-4">Contact Sales</h1>
                <p class="text-lg text-muted-foreground">Talk to an expert about how Zendesk can help your business.</p>
            </div>

            <x-ui.card class="bg-background">
                <x-ui.card-content class="pt-6">
                    @if($sent)
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-6">
                                <x-lucide-check class="w-8 h-8 text-green-600" />
                            </div>
                            <h2 class="text-2xl font-bold mb-2">Message sent!</h2>
                            <p class="text-muted-foreground">We'll get back to you shortly.</p>
                        </div>
                    @else
                        <form wire:submit="submit" class="space-y-6">
                            <div class="space-y-2">
                                <x-ui.label for="name">Full Name</x-ui.label>
                                <x-ui.input id="name" wire:model="name" placeholder="Jane Doe" required />
                                @error('name') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <x-ui.label for="email">Work Email</x-ui.label>
                                <x-ui.input id="email" type="email" wire:model="email" placeholder="jane@company.com" required />
                                @error('email') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="company">Company</x-ui.label>
                                <x-ui.input id="company" wire:model="company" placeholder="Acme Corp" required />
                                @error('company') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="message">How can we help?</x-ui.label>
                                <textarea id="message" wire:model="message" class="flex min-h-[120px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" required></textarea>
                                @error('message') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <x-ui.button type="submit" class="w-full">
                                <span wire:loading.remove>Send Message</span>
                                <span wire:loading>Sending...</span>
                            </x-ui.button>
                        </form>
                    @endif
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </section>
</div>
