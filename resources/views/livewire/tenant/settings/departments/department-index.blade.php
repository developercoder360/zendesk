<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Departments</span>
    </nav>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Departments</h1>
            <p class="text-sm text-muted-foreground">Organize your team into functional departments.</p>
        </div>
        <x-ui.button wire:click="create">Add Department</x-ui.button>
    </div>
    <x-ui.card>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Name</x-ui.table-head>
                    <x-ui.table-head>Description</x-ui.table-head>
                    <x-ui.table-head>Status</x-ui.table-head>
                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse ($departments as $department)
                    <x-ui.table-row>
                        <x-ui.table-cell class="font-medium">{{ $department->name }}</x-ui.table-cell>
                        <x-ui.table-cell>{{ $department->description ?? '-' }}</x-ui.table-cell>
                        <x-ui.table-cell>
                            @if ($department->is_active)
                                <x-ui.badge variant="outline" class="bg-green-500/10 text-green-500">Active</x-ui.badge>
                            @else
                                <x-ui.badge variant="secondary">Inactive</x-ui.badge>
                            @endif
                        </x-ui.table-cell>
                        <x-ui.table-cell class="text-right">
                            <x-ui.button variant="ghost" size="sm" wire:click="edit('{{ $department->id }}')">
                                Edit
                            </x-ui.button>
                            <x-ui.button variant="ghost" size="sm" wire:click="delete('{{ $department->id }}')"
                                class="text-red-500 hover:text-red-600">
                                Delete
                            </x-ui.button>
                        </x-ui.table-cell>
                    </x-ui.table-row>
                @empty
                    <x-ui.table-row>
                        <x-ui.table-cell colspan="4" class="h-24 text-center text-muted-foreground">
                            No departments found.
                        </x-ui.table-cell>
                    </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card>
    <!-- Modal -->
    <x-ui.dialog wire:model="isModalOpen">
        <x-ui.dialog-content>
            <x-ui.dialog-header>
                <x-ui.dialog-title>{{ $departmentId ? 'Edit Department' : 'Add Department' }}</x-ui.dialog-title>
            </x-ui.dialog-header>
            <form wire:submit="save">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <x-ui.label for="name">Name</x-ui.label>
                        <x-ui.input id="name" wire:model="name" />
                        @error('name')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid gap-2">
                        <x-ui.label for="description">Description (Optional)</x-ui.label>
                        <x-ui.textarea id="description" wire:model="description" />
                        @error('description')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="isActive" wire:model="isActive"
                            class="rounded border-input text-primary focus:ring-primary h-4 w-4">
                        <x-ui.label for="isActive">Active</x-ui.label>
                    </div>
                </div>
                <x-ui.dialog-footer>
                    <x-ui.button type="button" variant="outline"
                        wire:click="$set('isModalOpen', false)">Cancel</x-ui.button>
                    <x-ui.button type="submit">Save</x-ui.button>
                </x-ui.dialog-footer>
            </form>
        </x-ui.dialog-content>
    </x-ui.dialog>
</div>
