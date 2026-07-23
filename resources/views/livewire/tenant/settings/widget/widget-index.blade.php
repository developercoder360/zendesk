<div>
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Widget</span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Widget Configuration</h1>
            <p class="text-sm text-muted-foreground">Customize the look and feel of your chat widget.</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Embed Code Snippet -->
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Embed Snippet</x-ui.card-title>
                    <x-ui.card-description>Copy and paste this code snippet into your website's HTML before the closing &lt;/body&gt; tag.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-4">
                    <div class="relative">
                        <pre class="bg-muted p-4 rounded-md text-xs font-mono overflow-x-auto text-foreground border border-input">&lt;script 
    src="{{ request()->getSchemeAndHttpHost() }}/widget.js" 
    data-embed-key="{{ $embed_key }}" 
    async&gt;&lt;/script&gt;</pre>
                    </div>

                    <div class="flex items-center justify-between text-xs text-muted-foreground pt-1">
                        <span>Embed Key: <code class="font-mono">{{ $embed_key }}</code></span>
                        <x-ui.button variant="ghost" size="xs" wire:click="regenerateKey" wire:confirm="Are you sure you want to regenerate your embed key? Existing embeds will need to be updated.">Regenerate Key</x-ui.button>
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Customization Form -->
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Widget Branding & Content</x-ui.card-title>
                    <x-ui.card-description>Customize the look and feel of the chat widget displayed to your website visitors.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    @if($saved)
                        <div class="mb-4 p-3 bg-primary/10 border border-primary/20 text-primary text-sm rounded-md flex items-center justify-between">
                            <span>Widget settings saved successfully!</span>
                            <button wire:click="$set('saved', false)" class="text-xs underline">Dismiss</button>
                        </div>
                    @endif

                    <form wire:submit="save" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <x-ui.label for="primary_color">Brand / Primary Color</x-ui.label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" id="primary_color_picker" wire:model.live="primary_color" class="size-9 rounded border cursor-pointer p-0.5 bg-background border-input">
                                    <x-ui.input id="primary_color" wire:model="primary_color" placeholder="#0f172a" required />
                                </div>
                                @error('primary_color') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="welcome_text">Welcome Greeting</x-ui.label>
                                <x-ui.input id="welcome_text" wire:model="welcome_text" required />
                                @error('welcome_text') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="offline_message">Offline Message</x-ui.label>
                            <x-ui.textarea id="offline_message" wire:model="offline_message" rows="3" />
                            @error('offline_message') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <x-ui.label for="allowed_domains_text">Allowed Domains (One per line)</x-ui.label>
                            <x-ui.textarea id="allowed_domains_text" wire:model="allowed_domains_text" rows="3" placeholder="example.com&#10;app.example.com" />
                            <span class="text-xs text-muted-foreground">Restrict widget loading to specific domain origins. Leave blank to allow all domains.</span>
                            @error('allowed_domains_text') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end pt-4">
                            <x-ui.button type="submit">Save Widget Configuration</x-ui.button>
                        </div>
                    </form>
                </x-ui.card-content>
            </x-ui.card>

        </div>
    </div>
</div>
