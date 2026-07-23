<div>
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Widget</span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Widget Configuration</h1>
            <p class="text-sm text-muted-foreground">Customize your website chat widget, embed code, and domain security restrictions.</p>
        </div>
    </div>

    <div class="space-y-6">

        <!-- Embed Snippet & Security Card -->
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title class="flex items-center space-x-2">
                    <x-lucide-code class="size-5 text-primary" />
                    <span>Embed Snippet & Key</span>
                </x-ui.card-title>
                <x-ui.card-description>Copy and paste this code snippet into your website's HTML before the closing &lt;/body&gt; tag.</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                @php
                    $snippetText = '<script src="' . request()->getSchemeAndHttpHost() . '/widget.js" data-embed-key="' . $embed_key . '" async></script>';
                @endphp

                <div x-data="{ copied: false }" class="relative">
                    <pre class="bg-muted p-4 rounded-lg text-xs font-mono overflow-x-auto text-foreground border border-input leading-relaxed">{{ $snippetText }}</pre>

                    <button 
                        type="button"
                        x-on:click="navigator.clipboard.writeText('{{ $snippetText }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="absolute top-3 right-3 inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-md text-xs font-medium bg-background border border-input hover:bg-accent text-foreground transition-colors shadow-sm"
                    >
                        <template x-if="!copied">
                            <span class="flex items-center space-x-1">
                                <x-lucide-copy class="size-3.5" />
                                <span>Copy Code</span>
                            </span>
                        </template>
                        <template x-if="copied">
                            <span class="flex items-center space-x-1 text-emerald-600 dark:text-emerald-400">
                                <x-lucide-check class="size-3.5" />
                                <span>Copied!</span>
                            </span>
                        </template>
                    </button>
                </div>

                <div class="flex items-center justify-between text-xs text-muted-foreground pt-1 border-t border-border">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-foreground">Embed Key:</span>
                        <code class="font-mono bg-muted px-2 py-0.5 rounded border border-input select-all">{{ $embed_key }}</code>
                    </div>
                    <x-ui.button 
                        variant="outline" 
                        size="xs" 
                        wire:click="regenerateKey" 
                        wire:confirm="Are you sure you want to regenerate your embed key? Any website currently using the old key will be invalidated until updated."
                        class="text-destructive hover:text-destructive"
                    >
                        <x-lucide-refresh-cw class="size-3 mr-1" />
                        Regenerate Key
                    </x-ui.button>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Main Configuration & Live Preview Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Settings Form (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Branding & Content</x-ui.card-title>
                        <x-ui.card-description>Configure the visual theme and text displayed to website visitors.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <form wire:submit="save" class="space-y-5">

                            <!-- Primary Color -->
                            <div class="space-y-2">
                                <x-ui.label for="primary_color_input">Brand / Primary Color</x-ui.label>
                                <div class="flex items-center space-x-3">
                                    <input 
                                        type="color" 
                                        id="primary_color_picker" 
                                        wire:model.live="primary_color" 
                                        class="size-10 rounded-lg border cursor-pointer p-0.5 bg-background border-input shrink-0"
                                    >
                                    <x-ui.input 
                                        id="primary_color_input" 
                                        wire:model.live="primary_color" 
                                        placeholder="#0f172a" 
                                        class="font-mono"
                                        required 
                                    />
                                </div>
                                <span class="text-xs text-muted-foreground">Sets the widget header, floating button, and submit action button colors.</span>
                                @error('primary_color') <span class="block text-xs text-destructive mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Welcome Text -->
                            <div class="space-y-2">
                                <x-ui.label for="welcome_text">Welcome Greeting</x-ui.label>
                                <x-ui.input 
                                    id="welcome_text" 
                                    wire:model.live="welcome_text" 
                                    placeholder="Hi there! How can we help you today?" 
                                    required 
                                />
                                <span class="text-xs text-muted-foreground">Header tagline displayed when a visitor opens the widget.</span>
                                @error('welcome_text') <span class="block text-xs text-destructive mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Offline Message -->
                            <div class="space-y-2">
                                <x-ui.label for="offline_message">Offline Message</x-ui.label>
                                <x-ui.textarea 
                                    id="offline_message" 
                                    wire:model.live="offline_message" 
                                    rows="3" 
                                    placeholder="We are currently offline. Please leave a message!" 
                                />
                                <span class="text-xs text-muted-foreground">Notice displayed when no live human agents or AI assistants are available.</span>
                                @error('offline_message') <span class="block text-xs text-destructive mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Domain Security (Tag Input) -->
                            <div class="space-y-3 pt-2 border-t border-border">
                                <div>
                                    <x-ui.label for="new_domain_input">Allowed Domains (Security Restriction)</x-ui.label>
                                    <p class="text-xs text-muted-foreground mt-0.5">
                                        Strict security: The widget frame will <strong class="text-destructive">ONLY</strong> render on domains explicitly listed below. If empty, the widget remains inactive.
                                    </p>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <x-ui.input 
                                        id="new_domain_input" 
                                        wire:model="new_domain" 
                                        wire:keydown.enter.prevent="addDomain" 
                                        placeholder="e.g. example.com or app.mycompany.com" 
                                    />
                                    <x-ui.button type="button" variant="secondary" wire:click="addDomain" class="shrink-0">
                                        <x-lucide-plus class="size-4 mr-1.5" />
                                        Add Domain
                                    </x-ui.button>
                                </div>
                                @error('new_domain') <span class="block text-xs text-destructive">{{ $message }}</span> @enderror

                                <!-- Domain Tags List -->
                                <div class="flex flex-wrap gap-2 pt-1 min-h-[38px]">
                                    @forelse($allowed_domains as $index => $domain)
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                            <span>{{ $domain }}</span>
                                            <button 
                                                type="button" 
                                                wire:click="removeDomain({{ $index }})" 
                                                class="text-primary/70 hover:text-destructive focus:outline-none transition-colors ml-1"
                                                title="Remove domain"
                                            >
                                                <x-lucide-x class="size-3.5" />
                                            </button>
                                        </span>
                                    @empty
                                        <div class="p-3 bg-amber-500/10 border border-amber-500/20 rounded-md text-amber-700 dark:text-amber-400 text-xs flex items-center space-x-2 w-full">
                                            <x-lucide-alert-triangle class="size-4 shrink-0" />
                                            <span>No domains added yet. Add at least one domain (e.g. <code>localhost:8000</code> or <code>example.com</code>) to activate your widget.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <x-ui.button type="submit" class="w-full sm:w-auto">
                                    <x-lucide-save class="size-4 mr-1.5" />
                                    Save Widget Configuration
                                </x-ui.button>
                            </div>
                        </form>
                    </x-ui.card-content>
                </x-ui.card>
            </div>

            <!-- Right Column: Interactive Live Preview (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <x-ui.card class="sticky top-6">
                    <x-ui.card-header>
                        <x-ui.card-title class="flex items-center justify-between">
                            <span class="flex items-center space-x-2">
                                <x-lucide-eye class="size-4 text-primary" />
                                <span>Live Preview</span>
                            </span>
                            <span class="text-xs font-normal text-muted-foreground">Updates in real time</span>
                        </x-ui.card-title>
                        <x-ui.card-description>Preview how your widget looks to visitors on your website.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <!-- Simulated Website Container -->
                        <div class="relative bg-muted/40 rounded-xl border border-border p-4 min-h-[460px] flex flex-col justify-end overflow-hidden shadow-inner">
                            <div class="absolute inset-0 bg-grid-pattern opacity-5 pointer-events-none"></div>

                            <!-- Mock Website Header background -->
                            <div class="absolute top-0 left-0 right-0 p-3 bg-background/80 backdrop-blur border-b border-border/50 flex items-center justify-between text-xs text-muted-foreground">
                                <div class="flex items-center space-x-2">
                                    <div class="size-2.5 rounded-full bg-destructive/60"></div>
                                    <div class="size-2.5 rounded-full bg-amber-500/60"></div>
                                    <div class="size-2.5 rounded-full bg-emerald-500/60"></div>
                                    <span class="ml-2 font-mono text-[10px]">https://yourwebsite.com</span>
                                </div>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-muted-foreground/70">Preview</span>
                            </div>

                            <!-- Mock Floating Open Chat Panel -->
                            <div class="w-full bg-card rounded-xl border border-border shadow-xl overflow-hidden mb-3 text-card-foreground">
                                <!-- Dynamic Header -->
                                <div 
                                    class="p-4 text-white transition-colors duration-200"
                                    :style="'background-color: ' + $wire.primary_color"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-sm">Contact Support</h4>
                                            <p class="text-xs text-white/80 mt-0.5 line-clamp-1" x-text="$wire.welcome_text || 'Welcome!'"></p>
                                        </div>
                                        <div class="p-1 rounded bg-white/10 text-white/90">
                                            <x-lucide-x class="size-4" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Dynamic Offline Banner preview -->
                                <template x-if="$wire.offline_message">
                                    <div class="p-2.5 bg-amber-500/10 border-b border-amber-500/20 text-amber-700 dark:text-amber-300 text-xs flex items-start space-x-2">
                                        <x-lucide-clock class="size-3.5 mt-0.5 shrink-0" />
                                        <span x-text="$wire.offline_message"></span>
                                    </div>
                                </template>

                                <!-- Mock Form Preview -->
                                <div class="p-3.5 space-y-2.5 text-xs">
                                    <div>
                                        <label class="block text-[11px] font-medium text-muted-foreground mb-1">Name</label>
                                        <div class="h-7 w-full rounded border border-input bg-muted/30 px-2 flex items-center text-muted-foreground text-[11px]">Jane Visitor</div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-medium text-muted-foreground mb-1">Email</label>
                                        <div class="h-7 w-full rounded border border-input bg-muted/30 px-2 flex items-center text-muted-foreground text-[11px]">jane@example.com</div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-medium text-muted-foreground mb-1">Description</label>
                                        <div class="h-12 w-full rounded border border-input bg-muted/30 p-2 text-muted-foreground text-[11px]">How can we assist you?</div>
                                    </div>

                                    <button 
                                        type="button" 
                                        class="w-full py-2 rounded-lg text-white font-medium text-xs transition-colors duration-200 shadow-sm"
                                        :style="'background-color: ' + $wire.primary_color"
                                    >
                                        Submit Ticket
                                    </button>
                                </div>
                            </div>

                            <!-- Mock Floating Launcher Button -->
                            <div class="flex justify-end">
                                <div 
                                    class="size-12 rounded-full shadow-lg flex items-center justify-center text-white cursor-pointer transition-colors duration-200"
                                    :style="'background-color: ' + $wire.primary_color"
                                >
                                    <x-lucide-message-square class="size-6" />
                                </div>
                            </div>

                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>

        </div>

    </div>
</div>
