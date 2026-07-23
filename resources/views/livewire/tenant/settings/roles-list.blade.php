<div class="space-y-6 max-w-6xl mx-auto">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Roles & Permissions</span>
    </nav>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Roles & Permissions</h1>
            <p class="text-sm text-muted-foreground">Manage who has access to what in your workspace.</p>
        </div>
        <x-ui.button wire:navigate href="{{ route('tenant.settings.roles.create') }}">Create Role</x-ui.button>
    </div>

    @error('error')
        <div class="mb-4 bg-destructive/10 text-destructive p-3 rounded-md text-sm">
            {{ $message }}
        </div>
    @enderror

    <div class="rounded-md border">
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Role Name</x-ui.table-head>
                    <x-ui.table-head>Users</x-ui.table-head>
                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse ($roles as $role)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $role->name }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $role->users_count }}</x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="ghost" size="sm" wire:navigate href="{{ route('tenant.settings.roles.edit', $role) }}">Edit</x-ui.button>
                            @if(!in_array($role->name, ['Owner', 'Company Admin']))
                                <x-ui.button variant="ghost" size="sm" class="text-destructive" wire:click="deleteRole({{ $role->id }})" wire:confirm="Are you sure?">Delete</x-ui.button>
                            @endif
                        </x-ui.table-cell>
                    </x-ui.table-row>
                @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="3" class="text-center text-muted-foreground h-24">No roles found.</x-ui.table-cell>
                    </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </div>
</div>
