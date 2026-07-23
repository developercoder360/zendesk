<div class="space-y-6 max-w-6xl mx-auto">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Notifications</span>
    </nav>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Notification Preferences</h1>
            <p class="text-sm text-muted-foreground">Configure email and browser alerts for events.</p>
        </div>
    </div>
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Alerts & Preferences</x-ui.card-title>
            <x-ui.card-description>Choose how and when you receive notifications about tickets, customer messages, and
                assignments.</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content>
            @if ($saved)
                <div
                    class="mb-4 p-3 bg-primary/10 border border-primary/20 text-primary text-sm rounded-md flex items-center justify-between">
                    <span>Notification preferences saved!</span>
                    <button wire:click="$set('saved', false)" class="text-xs underline">Dismiss</button>
                </div>
            @endif
            <form wire:submit="save" class="space-y-6">
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">Email Notifications
                    </h4>
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <div class="font-medium text-sm">New Ticket Created</div>
                            <div class="text-xs text-muted-foreground">Receive an email whenever a customer submits a
                                new ticket.</div>
                        </div>
                        <x-ui.switch wire:model="email_new_ticket" />
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <div class="font-medium text-sm">Ticket Assignment</div>
                            <div class="text-xs text-muted-foreground">Receive an email when a ticket is assigned
                                directly to you.</div>
                        </div>
                        <x-ui.switch wire:model="email_assignment" />
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <div class="font-medium text-sm">Mentions & Internal Notes</div>
                            <div class="text-xs text-muted-foreground">Receive an email when another agent mentions you
                                in a ticket.</div>
                        </div>
                        <x-ui.switch wire:model="email_mention" />
                    </div>
                </div>
                <div class="space-y-4 pt-2">
                    <h4 class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">In-App & Desktop
                    </h4>
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <div class="font-medium text-sm">Sound Alerts</div>
                            <div class="text-xs text-muted-foreground">Play a chime sound when a new live chat or
                                message arrives.</div>
                        </div>
                        <x-ui.switch wire:model="sound_alerts" />
                    </div>
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <div class="font-medium text-sm">Desktop Push Notifications</div>
                            <div class="text-xs text-muted-foreground">Show browser notifications even when Zendesk tab
                                is in background.</div>
                        </div>
                        <x-ui.switch wire:model="desktop_push" />
                    </div>
                </div>
                <div class="flex justify-end pt-4">
                    <x-ui.button type="submit">Save Preferences</x-ui.button>
                </div>
            </form>
        </x-ui.card-content>
    </x-ui.card>
</div>
