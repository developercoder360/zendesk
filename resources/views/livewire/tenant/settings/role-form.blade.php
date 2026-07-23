<div class="max-w-4xl mx-auto space-y-6">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <a wire:navigate href="{{ route('tenant.settings.roles.index') }}" class="hover:text-foreground transition-colors">Roles</a>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">{{ $role ? 'Edit Role' : 'Create Role' }}</span>
    </nav>

    <div class="mb-6 flex items-center gap-4">
        <x-ui.button variant="outline" size="icon" wire:navigate href="{{ route('tenant.settings.roles.index') }}">
            <x-lucide-arrow-left class="w-4 h-4" />
        </x-ui.button>
        <div>
            <h1 class="text-2xl font-bold tracking-tight">{{ $role ? 'Edit Role' : 'Create Role' }}</h1>
            <p class="text-sm text-muted-foreground">Define what this role can and cannot do.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-8">
        <div class="space-y-2">
            <x-ui.label for="name">Role Name</x-ui.label>
            <x-ui.input wire:model="name" id="name" required :disabled="$role && in_array($role->name, ['Owner', 'Company Admin'])" />
            @error('name') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
        </div>

        <div class="space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">Permissions</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($permissionGroups as $module => $perms)
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title class="text-base">{{ $module }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-3">
                            @foreach($perms as $perm)
                                <label class="flex items-center gap-3 text-sm cursor-pointer hover:bg-muted/50 p-1 rounded-md transition-colors">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm }}" class="w-4 h-4 rounded border-input text-primary focus:ring-primary" 
                                        @if($role && $role->name === 'Owner') disabled checked @endif
                                    >
                                    <span class="capitalize">{{ str_replace('_', ' ', $perm) }}</span>
                                </label>
                            @endforeach
                        </x-ui.card-content>
                    </x-ui.card>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-4 border-t pt-6">
            <x-ui.button type="button" variant="outline" wire:navigate href="{{ route('tenant.settings.roles.index') }}">Cancel</x-ui.button>
            <x-ui.button type="submit">Save Role</x-ui.button>
        </div>
    </form>
</div>
