<div class="flex h-full flex-col">
    <div class="flex h-16 shrink-0 items-center px-6">
        <a href="{{ route('central.dashboard') }}" wire:navigate class="flex items-center gap-2 font-semibold">
            <span class="bg-primary text-primary-foreground flex size-8 items-center justify-center rounded-lg">
                <x-lucide-cloud class="size-5" />
            </span> 
            Zendesk SaaS
        </a>
    </div>

    <div class="flex-1 overflow-auto py-4">
        <nav class="grid gap-1 px-4">
            <div class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Overview</div>
            
            <a href="{{ route('central.dashboard') }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('central.dashboard'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('central.dashboard')])>
                <x-lucide-layout-dashboard class="size-4" />
                Dashboard
            </a>

            <div class="mb-2 mt-6 px-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Manage</div>

            <a href="{{ route('central.billing') ?? '#' }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('central.billing'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('central.billing')])>
                <x-lucide-credit-card class="size-4" />
                Billing
            </a>

            <a href="{{ route('central.account') ?? '#' }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('central.account'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('central.account')])>
                <x-lucide-user class="size-4" />
                Account Settings
            </a>
        </nav>
    </div>

    <div class="mt-auto p-4">
        <x-ui.card class="bg-muted/50 overflow-hidden shadow-none">
            <x-ui.card-content class="p-4 text-center">
                <div class="mx-auto mb-2 flex size-10 items-center justify-center rounded-full bg-background shadow-sm">
                    <x-lucide-life-buoy class="size-5 text-primary" />
                </div>
                <h4 class="mb-1 text-sm font-semibold">Need help?</h4>
                <p class="mb-3 text-xs text-muted-foreground">Check our docs or contact support.</p>
                <x-ui.button variant="outline" size="sm" class="w-full">Documentation</x-ui.button>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
