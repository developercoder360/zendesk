<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Users</h1>
            <p class="text-sm text-muted-foreground">Manage your team members and their access levels.</p>
        </div>
        <x-ui.button wire:click="openCreateModal">
            <x-lucide-plus class="mr-2 size-4" />
            Add User
        </x-ui.button>
    </div>

    <x-ui.card>
        <div class="p-4 border-b flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Search users by name or email..." class="pl-9 w-full max-w-sm" />
            </div>
            
            <x-ui.select wire:model.live="roleFilter" class="sm:w-[180px]">
                <x-ui.select-trigger>
                    <x-ui.select-value placeholder="All Roles" />
                </x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="">All Roles</x-ui.select-item>
                    <x-ui.select-item value="owner">Owner</x-ui.select-item>
                    <x-ui.select-item value="manager">Manager</x-ui.select-item>
                    <x-ui.select-item value="agent">Agent</x-ui.select-item>
                </x-ui.select-content>
            </x-ui.select>
        </div>

        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Role</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Position</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                        <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($users as $tenantUser)
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-10 items-center justify-center rounded-full bg-primary/10 text-primary font-semibold">
                                        {{ substr($tenantUser->user->name ?? '?', 0, 2) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $tenantUser->user->name ?? 'Unknown User' }}</span>
                                        <span class="text-xs text-muted-foreground">{{ $tenantUser->user->email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 align-middle capitalize">{{ $tenantUser->user->role ?? 'N/A' }}</td>
                            <td class="p-4 align-middle text-muted-foreground">{{ $tenantUser->position ?? '—' }}</td>
                            <td class="p-4 align-middle">
                                @if($tenantUser->is_active)
                                    <x-ui.badge variant="outline" class="bg-primary/10 text-primary border-primary/20">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="destructive" class="opacity-70">Inactive</x-ui.badge>
                                @endif
                            </td>
                            <td class="p-4 align-middle text-right">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button variant="ghost" size="sm" wire:click="openEditModal({{ $tenantUser->user_id }})">
                                        <x-lucide-pencil class="size-4" />
                                    </x-ui.button>
                                    @if($tenantUser->user_id !== auth()->id())
                                        <x-ui.button variant="ghost" size="sm" class="text-destructive hover:text-destructive" wire:click="openDeleteModal({{ $tenantUser->user_id }})">
                                            <x-lucide-trash-2 class="size-4" />
                                        </x-ui.button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-muted-foreground">
                                No users found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t">
                {{ $users->links() }}
            </div>
        @endif
    </x-ui.card>

    <!-- Create/Edit Modal -->
    @if($showCreateModal || $showEditModal)
    <div class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm transition-all duration-100">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg sm:rounded-lg">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">
                    {{ $showCreateModal ? 'Add New User' : 'Edit User' }}
                </h2>
                <p class="text-sm text-muted-foreground">
                    {{ $showCreateModal ? 'Invite a new team member to your workspace.' : 'Update team member details.' }}
                </p>
            </div>

            <form wire:submit="{{ $showCreateModal ? 'save' : 'update' }}" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Full Name *</label>
                        <x-ui.input wire:model="form.name" required />
                        @error('form.name') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Email *</label>
                        <x-ui.input type="email" wire:model="form.email" required />
                        @error('form.email') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Password {!! $showEditModal ? '<span class="text-xs text-muted-foreground">(leave blank to keep)</span>' : '*' !!}</label>
                        <x-ui.input type="password" wire:model="form.password" {{ $showCreateModal ? 'required' : '' }} />
                        @error('form.password') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Role *</label>
                        <x-ui.select wire:model="form.role">
                            <x-ui.select-trigger>
                                <x-ui.select-value placeholder="Select Role" />
                            </x-ui.select-trigger>
                            <x-ui.select-content>
                                <x-ui.select-item value="agent">Agent</x-ui.select-item>
                                <x-ui.select-item value="manager">Manager</x-ui.select-item>
                                <x-ui.select-item value="owner">Owner</x-ui.select-item>
                            </x-ui.select-content>
                        </x-ui.select>
                        @error('form.role') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Position / Title</label>
                        <x-ui.input wire:model="form.position" />
                        @error('form.position') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Phone</label>
                        <x-ui.input wire:model="form.phone" />
                        @error('form.phone') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="is_active" wire:model="form.is_active" class="size-4 rounded border-gray-300 text-primary focus:ring-primary" />
                    <label for="is_active" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Active Account
                    </label>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 pt-4">
                    <x-ui.button type="button" variant="outline" wire:click="$set('showCreateModal', false); $set('showEditModal', false)">Cancel</x-ui.button>
                    <x-ui.button type="submit">
                        {{ $showCreateModal ? 'Create User' : 'Save Changes' }}
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm transition-all duration-100">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg sm:rounded-lg">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">Are you absolutely sure?</h2>
                <p class="text-sm text-muted-foreground">
                    This action cannot be undone. This will permanently delete 
                    <span class="font-semibold text-foreground">{{ $userToDelete?->name }}</span> 
                    and remove their data from our servers.
                </p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 mt-4">
                <x-ui.button variant="outline" wire:click="$set('showDeleteModal', false)">Cancel</x-ui.button>
                <x-ui.button variant="destructive" wire:click="delete">Delete User</x-ui.button>
            </div>
        </div>
    </div>
    @endif
</div>
