<div class="h-screen w-screen overflow-hidden font-sans">
    @if ($isNotConfigured)
        <!-- Unconfigured State (Allowed Domains Empty) -->
        <div class="h-full w-full flex items-center justify-center p-4 bg-muted/20">
            <x-ui.card class="w-full max-w-sm border border-amber-500/30 shadow-lg text-center bg-card">
                <x-ui.card-header class="pb-3">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 mb-3">
                        <x-lucide-settings class="size-6" />
                    </div>
                    <x-ui.card-title class="text-base font-bold text-foreground">Widget Not Activated</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-2 text-xs text-muted-foreground">
                    <p>{{ $statusMessage }}</p>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    @elseif ($isBlocked)
        <!-- Domain Restricted / Blocked State -->
        <div class="h-full w-full flex items-center justify-center p-4 bg-muted/20">
            <x-ui.card class="w-full max-w-sm border border-destructive/30 shadow-lg text-center bg-card">
                <x-ui.card-header class="pb-3">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-destructive/10 text-destructive mb-3">
                        <x-lucide-shield-alert class="size-6" />
                    </div>
                    <x-ui.card-title class="text-base font-bold text-foreground">Access Restricted</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-2 text-xs text-muted-foreground">
                    <p>{{ $statusMessage }}</p>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    @elseif ($success)
        <!-- Submission Success State -->
        <div class="h-full w-full flex items-center justify-center p-4 bg-card">
            <x-ui.card class="w-full border-none shadow-none text-center bg-card">
                <x-ui.card-header>
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 mb-4">
                        <x-lucide-check class="size-6" />
                    </div>
                    <x-ui.card-title class="text-xl font-bold">Ticket Submitted!</x-ui.card-title>
                    <x-ui.card-description class="mt-2 text-sm">
                        Thank you for reaching out. Our support team will get back to you shortly.
                    </x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-footer class="flex justify-center mt-4">
                    <x-ui.button variant="outline" size="sm" onclick="if (window.parent) window.parent.postMessage('closeWidget', '*')">
                        Close Window
                    </x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </div>
    @else
        <!-- Active Widget Ticket Form -->
        <x-ui.card class="w-full h-full border-0 rounded-none shadow-none flex flex-col bg-card overflow-hidden">
            <!-- Widget Header -->
            <x-ui.card-header 
                class="shrink-0 p-4 text-white transition-colors duration-200"
                style="background-color: {{ $primaryColor }};"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <x-ui.card-title class="text-base font-semibold text-white">Contact Support</x-ui.card-title>
                        <x-ui.card-description class="text-xs text-white/80 mt-0.5">{{ $welcomeText }}</x-ui.card-description>
                    </div>
                    <button 
                        type="button" 
                        class="text-white/80 hover:text-white p-1 rounded hover:bg-white/10 transition-colors"
                        onclick="if (window.parent) window.parent.postMessage('closeWidget', '*')"
                        aria-label="Close widget"
                    >
                        <x-lucide-x class="size-4" />
                    </button>
                </div>
            </x-ui.card-header>

            <!-- Offline Banner (if applicable) -->
            @if ($isOffline && !empty($offlineMessage))
                <div class="p-3 bg-amber-500/10 border-b border-amber-500/20 text-amber-800 dark:text-amber-300 text-xs flex items-start space-x-2 shrink-0">
                    <x-lucide-clock class="size-4 mt-0.5 shrink-0" />
                    <span>{{ $offlineMessage }}</span>
                </div>
            @endif

            <!-- Form Content -->
            <x-ui.card-content class="flex-1 overflow-y-auto p-4 space-y-3.5">
                <form wire:submit="submit" id="widget-ticket-form" class="space-y-3.5">
                    <div class="space-y-1">
                        <x-ui.label for="name" class="text-xs">Your Name</x-ui.label>
                        <x-ui.input wire:model="name" id="name" placeholder="Jane Doe" class="h-9 text-xs" />
                        @error('name') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1">
                        <x-ui.label for="email" class="text-xs">Email Address</x-ui.label>
                        <x-ui.input wire:model="email" id="email" type="email" placeholder="jane@example.com" class="h-9 text-xs" />
                        @error('email') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1">
                        <x-ui.label for="subject" class="text-xs">Subject</x-ui.label>
                        <x-ui.input wire:model="subject" id="subject" placeholder="How can we help?" class="h-9 text-xs" />
                        @error('subject') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1">
                        <x-ui.label for="description" class="text-xs">Message / Details</x-ui.label>
                        <x-ui.textarea wire:model="description" id="description" rows="3" placeholder="Please describe your question or issue in detail..." class="text-xs" />
                        @error('description') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                </form>
            </x-ui.card-content>

            <!-- Form Footer -->
            <x-ui.card-footer class="shrink-0 border-t p-3 bg-card">
                <x-ui.button 
                    type="submit" 
                    form="widget-ticket-form"
                    class="w-full text-white font-medium text-xs h-9 transition-colors duration-200"
                    style="background-color: {{ $primaryColor }};"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="submit">Submit Ticket</span>
                    <span wire:loading wire:target="submit">Submitting...</span>
                </x-ui.button>
            </x-ui.card-footer>
        </x-ui.card>
    @endif
</div>
