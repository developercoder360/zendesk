<div>
    @if ($success)
        <x-ui.card class="w-full shadow-none border-none bg-card">
            <x-ui.card-header class="text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 mb-4">
                    <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <x-ui.card-title class="text-xl">Ticket Submitted!</x-ui.card-title>
                <x-ui.card-description class="mt-2">
                    Thank you for reaching out. Our support team will get back to you shortly.
                </x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-footer class="flex justify-center mt-4">
                <x-ui.button variant="outline" @click="window.parent.postMessage('closeWidget', '*'); setTimeout(() => $wire.set('success', false), 500)">
                    Close Window
                </x-ui.button>
            </x-ui.card-footer>
        </x-ui.card>
    @else
        <x-ui.card class="w-full h-full border-0 rounded-none shadow-none flex flex-col bg-card" style="height: 100vh;">
            <x-ui.card-header class="shrink-0 pt-6 pb-4 border-b bg-muted/30">
                <div class="flex justify-between items-start">
                    <div>
                        <x-ui.card-title class="text-lg">Contact Support</x-ui.card-title>
                        <x-ui.card-description>How can we help you today?</x-ui.card-description>
                    </div>
                    <button type="button" class="text-muted-foreground hover:text-foreground p-1" @click="window.parent.postMessage('closeWidget', '*')" aria-label="Close widget">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            </x-ui.card-header>
            
            <x-ui.card-content class="flex-1 overflow-y-auto py-6">
                <form wire:submit="submit" class="space-y-4">
                    <div class="space-y-2">
                        <x-ui.label for="name">Name</x-ui.label>
                        <x-ui.input wire:model="name" id="name" placeholder="John Doe" />
                        @error('name') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="email">Email</x-ui.label>
                        <x-ui.input wire:model="email" id="email" type="email" placeholder="john@example.com" />
                        @error('email') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="subject">Subject</x-ui.label>
                        <x-ui.input wire:model="subject" id="subject" placeholder="What is this regarding?" />
                        @error('subject') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="description">Description</x-ui.label>
                        <x-ui.textarea wire:model="description" id="description" rows="4" placeholder="Please describe your issue in detail..." />
                        @error('description') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                    </div>
                </form>
            </x-ui.card-content>

            <x-ui.card-footer class="shrink-0 border-t py-4 bg-card">
                <x-ui.button class="w-full" wire:click="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">Submit Ticket</span>
                    <span wire:loading wire:target="submit">Submitting...</span>
                </x-ui.button>
            </x-ui.card-footer>
        </x-ui.card>
    @endif
</div>
