<div class="flex h-full flex-col">
    <div class="flex h-16 shrink-0 items-center px-6">
        <a href="{{ route('tenant.dashboard') }}" wire:navigate class="flex items-center gap-2 font-semibold">
            <span class="bg-primary text-primary-foreground flex size-8 items-center justify-center rounded-lg">
                <x-lucide-box class="size-5" />
            </span> 
            {{ tenant()->name ?? 'Tenant' }}
        </a>
    </div>

    <div class="flex-1 overflow-auto py-4">
        <nav class="grid gap-1 px-4">
            <div class="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Workspace</div>
            
            <a href="{{ route('tenant.dashboard') }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('tenant.dashboard'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.dashboard')])>
                <x-lucide-layout-dashboard class="size-4" />
                Dashboard
            </a>

            <a href="{{ route('tenant.tickets.index') }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('tenant.tickets.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.tickets.*')])>
                <x-lucide-ticket class="size-4" />
                Tickets
            </a>

            <a href="{{ route('tenant.tickets.history') }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('tenant.tickets.history'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.tickets.history')])>
                <x-lucide-history class="size-4" />
                History
            </a>

            <a href="{{ route('tenant.visitors.index') }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('tenant.visitors.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.visitors.*')])>
                <x-lucide-users class="size-4" />
                Visitors
            </a>

            <a href="{{ route('tenant.customers.index') }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('tenant.customers.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.customers.*')])>
                <x-lucide-user-check class="size-4" />
                Customers
            </a>

            <a href="{{ route('tenant.knowledge-base.index') }}" wire:navigate @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('tenant.knowledge-base.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.knowledge-base.*')])>
                <x-lucide-book class="size-4" />
                Knowledge Base
            </a>

            <a href="#" wire:navigate class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-all text-muted-foreground hover:bg-accent hover:text-accent-foreground">
                <x-lucide-bar-chart class="size-4" />
                Reports
            </a>

            <div class="mb-2 mt-6 px-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Administration</div>

            <div x-data="{ open: {{ request()->routeIs('tenant.settings.*') || request()->routeIs('tenant.agents.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" @class(['flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm transition-all', 'bg-accent text-accent-foreground' => request()->routeIs('tenant.settings.*') || request()->routeIs('tenant.agents.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !(request()->routeIs('tenant.settings.*') || request()->routeIs('tenant.agents.*'))])>
                    <div class="flex items-center gap-3">
                        <x-lucide-settings class="size-4" />
                        Settings
                    </div>
                    <x-lucide-chevron-down class="size-4 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
                </button>
                <div x-show="open" x-collapse class="pl-9 pr-3 py-1 space-y-1">
                    <a href="{{ route('tenant.settings.personal.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.personal.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.personal.*')])>Personal</a>
                    
                    @can('view_settings')
                    <a href="{{ route('tenant.settings.company.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.company.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.company.*')])>Company</a>
                    @endcan

                    <a href="{{ route('tenant.agents.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.agents.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.agents.*')])>Agents</a>
                    
                    @can('view_settings')
                    <a href="{{ route('tenant.settings.departments.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.departments.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.departments.*')])>Departments</a>
                    <a href="{{ route('tenant.settings.teams.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.teams.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.teams.*')])>Teams</a>
                    <a href="{{ route('tenant.settings.roles.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.roles.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.roles.*')])>Roles & Permissions</a>
                    <a href="{{ route('tenant.settings.shortcuts.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.shortcuts.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.shortcuts.*')])>Shortcuts</a>
                    <a href="{{ route('tenant.settings.banned.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.banned.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.banned.*')])>Banned</a>
                    <a href="{{ route('tenant.settings.widget.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.widget.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.widget.*')])>Widget</a>
                    @endcan

                    <a href="{{ route('tenant.settings.notifications.index') }}" wire:navigate @class(['block rounded-md px-3 py-1.5 text-sm transition-all', 'bg-accent font-medium text-foreground' => request()->routeIs('tenant.settings.notifications.*'), 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => !request()->routeIs('tenant.settings.notifications.*')])>Notifications</a>
                </div>
            </div>
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
