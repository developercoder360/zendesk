<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Shortcuts</span>
    </nav>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Shortcuts</h1>
            <p class="text-sm text-muted-foreground">Manage canned responses for quicker replies.</p>
        </div>
        <!-- Add Shortcut Button -->
        <x-ui.button wire:click="create"><x-lucide-plus class="size-4 mr-1.5" />Add shortcut</x-ui.button>
    </div>
    <div class="py-8">
        <!-- Toolbar -->
        <div class="flex items-center gap-3 mb-4">
            <!-- Search -->
            <div class="relative flex-1 max-w-xs">
                <x-lucide-search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <x-ui.input type="search" placeholder="Search shortcuts..." class="pl-8"
                    wire:model.live.debounce.300ms="search" />
            </div>
            <!-- Spacer -->
            <div class="flex-1"></div>
            <!-- Bulk Delete (shown when selected) -->
            @if (count($selectedIds) > 0)
                <x-ui.button variant="destructive" size="sm" wire:click="deleteSelected"
                    wire:confirm="Delete {{ count($selectedIds) }} selected shortcut(s)?">
                    <x-lucide-trash-2 class="size-3.5 mr-1.5" />
                    Delete ({{ count($selectedIds) }})
                </x-ui.button>
            @endif
            <!-- Total count -->
            <span class="text-sm text-muted-foreground whitespace-nowrap">
                {{ $total }} {{ Str::plural('shortcut', $total) }}
            </span>
            <!-- Filter dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm border border-input rounded-md bg-background text-foreground hover:bg-accent transition-colors">
                    <x-lucide-filter class="size-3.5 text-muted-foreground" />
                    Filter shortcuts
                    <x-lucide-chevron-down class="size-3.5 text-muted-foreground" />
                </button>
                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute right-0 mt-1 w-52 rounded-md border border-border bg-popover shadow-md z-20 py-1">
                    <div class="px-2 py-1.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                        Availability</div>
                    <button wire:click="$set('filterAgent', 'all')" @click="open = false"
                        class="w-full text-left flex items-center gap-2 px-3 py-1.5 text-sm hover:bg-accent transition-colors {{ $filterAgent === 'all' ? 'font-semibold text-foreground' : 'text-muted-foreground' }}">
                        @if ($filterAgent === 'all')
                            <x-lucide-check class="size-3.5" />
                        @else
                            <span class="size-3.5"></span>
                        @endif
                        All shortcuts
                    </button>
                    <button wire:click="$set('filterAgent', 'global')" @click="open = false"
                        class="w-full text-left flex items-center gap-2 px-3 py-1.5 text-sm hover:bg-accent transition-colors {{ $filterAgent === 'global' ? 'font-semibold text-foreground' : 'text-muted-foreground' }}">
                        @if ($filterAgent === 'global')
                            <x-lucide-check class="size-3.5" />
                        @else
                            <span class="size-3.5"></span>
                        @endif
                        All agents (global)
                    </button>
                    @if ($agents->count())
                        <div class="border-t border-border my-1"></div>
                        <div class="px-2 py-1.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                            By Agent</div>
                        @foreach ($agents as $agent)
                            <button wire:click="$set('filterAgent', '{{ $agent->id }}')" @click="open = false"
                                class="w-full text-left flex items-center gap-2 px-3 py-1.5 text-sm hover:bg-accent transition-colors {{ $filterAgent == $agent->id ? 'font-semibold text-foreground' : 'text-muted-foreground' }}">
                                @if ($filterAgent == $agent->id)
                                    <x-lucide-check class="size-3.5" />
                                @else
                                    <span class="size-3.5"></span>
                                @endif
                                {{ $agent->user->name ?? 'Agent #' . $agent->id }}
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <!-- Table -->
        <x-ui.card class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border bg-muted/40">
                            <!-- Select All -->
                            <th class="w-10 px-4 py-3 text-left">
                                <input type="checkbox" wire:model.live="selectAll"
                                    class="rounded border-input size-4 accent-primary cursor-pointer" />
                            </th>
                            <!-- Shortcut column — sortable -->
                            <th class="px-4 py-3 text-left font-semibold text-foreground">
                                <button wire:click="sortBy('shortcut_key')"
                                    class="inline-flex items-center gap-1 hover:text-primary transition-colors group">
                                    Shortcut
                                    <span class="inline-flex flex-col text-muted-foreground group-hover:text-primary">
                                        @if ($sortField === 'shortcut_key')
                                            @if ($sortDirection === 'asc')
                                                <x-lucide-arrow-up class="size-3.5" />
                                            @else
                                                <x-lucide-arrow-down class="size-3.5" />
                                            @endif
                                        @else
                                            <x-lucide-chevrons-up-down class="size-3.5 opacity-40" />
                                        @endif
                                    </span>
                                </button>
                            </th>
                            <!-- Message -->
                            <th class="px-4 py-3 text-left font-semibold text-foreground">
                                <button wire:click="sortBy('title')"
                                    class="inline-flex items-center gap-1 hover:text-primary transition-colors group">
                                    Message
                                    <span class="inline-flex flex-col text-muted-foreground group-hover:text-primary">
                                        @if ($sortField === 'title')
                                            @if ($sortDirection === 'asc')
                                                <x-lucide-arrow-up class="size-3.5" />
                                            @else
                                                <x-lucide-arrow-down class="size-3.5" />
                                            @endif
                                        @else
                                            <x-lucide-chevrons-up-down class="size-3.5 opacity-40" />
                                        @endif
                                    </span>
                                </button>
                            </th>
                            <!-- Available for -->
                            <th class="px-4 py-3 text-left font-semibold text-foreground">Available for</th>
                            <!-- Tags (no tags system yet — placeholder) -->
                            <th class="px-4 py-3 text-left font-semibold text-foreground">Tags</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($shortcuts as $item)
                            <tr class="hover:bg-muted/30 transition-colors cursor-pointer {{ in_array((string) $item->id, $selectedIds) ? 'bg-primary/5' : '' }}"
                                wire:click="edit({{ $item->id }})">
                                <!-- Row checkbox (stop propagation so click doesn't open modal when checking) -->
                                <td class="px-4 py-3.5" wire:click.stop>
                                    <input type="checkbox" wire:model.live="selectedIds" value="{{ $item->id }}"
                                        class="rounded border-input size-4 accent-primary cursor-pointer" />
                                </td>
                                <!-- Shortcut key -->
                                <td class="px-4 py-3.5">
                                    <code
                                        class="inline-flex items-center px-2 py-0.5 rounded bg-muted text-foreground font-mono text-xs font-semibold">
                                        /{{ $item->shortcut_key }}
                                    </code>
                                </td>
                                <!-- Message (title + body excerpt — blue-ish tint like screenshot) -->
                                <td class="px-4 py-3.5 max-w-md">
                                    <div class="font-medium text-foreground text-sm mb-0.5">{{ $item->title }}
                                    </div>
                                    <div class="text-primary/70 text-[13px] leading-snug line-clamp-2">
                                        {{ $item->body }}
                                    </div>
                                </td>
                                <!-- Available for -->
                                <td class="px-4 py-3.5 text-sm text-muted-foreground whitespace-nowrap">
                                    @if (is_null($item->tenant_user_id))
                                        <div class="flex items-center gap-1.5">
                                            <x-lucide-users class="size-3.5 text-muted-foreground" />
                                            All agents
                                        </div>
                                    @elseif($item->tenantUser && $item->tenantUser->user)
                                        <div class="flex items-center gap-1.5">
                                            <div
                                                class="size-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-bold shrink-0">
                                                {{ substr($item->tenantUser->user->name, 0, 1) }}
                                            </div>
                                            {{ $item->tenantUser->user->name }}
                                        </div>
                                    @else
                                        <span class="text-muted-foreground italic">Unknown</span>
                                    @endif
                                </td>
                                <!-- Tags -->
                                <td class="px-4 py-3.5">
                                    @if (!empty($item->tags))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($item->tags as $tag)
                                                <x-ui.badge variant="secondary"
                                                    class="text-[10px] px-1.5 py-0">{{ $tag }}</x-ui.badge>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-muted-foreground/50 italic">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-16 text-center text-muted-foreground">
                                    <x-lucide-inbox class="size-8 mx-auto mb-2 opacity-30" />
                                    <p class="text-sm">No shortcuts found.</p>
                                    @if ($search)
                                        <p class="text-xs mt-1">Try adjusting your search term.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($shortcuts->hasPages())
                <div class="px-4 py-3 border-t border-border flex items-center justify-between">
                    <p class="text-xs text-muted-foreground">
                        Showing {{ $shortcuts->firstItem() }}–{{ $shortcuts->lastItem() }} of
                        {{ $shortcuts->total() }}
                    </p>
                    {{ $shortcuts->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
    <!-- Add / Edit Modal -->
    <x-ui.dialog wire:model="isModalOpen">
        <x-ui.dialog-content class="max-w-lg">
            <x-ui.dialog-header>
                <x-ui.dialog-title>{{ $shortcutId ? 'Edit Shortcut' : 'Add Shortcut' }}</x-ui.dialog-title>
                <x-ui.dialog-description>
                    {{ $shortcutId ? 'Update your canned response.' : 'Create a new canned response shortcut.' }}
                </x-ui.dialog-description>
            </x-ui.dialog-header>
            <form wire:submit="save">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <x-ui.label for="modal-title">Title</x-ui.label>
                        <x-ui.input id="modal-title" wire:model="title" placeholder="e.g. Greeting message" />
                        @error('title')
                            <span class="text-xs text-destructive">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid gap-2">
                        <x-ui.label for="modal-shortcut">Shortcut Trigger</x-ui.label>
                        <div class="flex items-center">
                            <span
                                class="inline-flex items-center bg-muted px-3 h-9 border border-r-0 border-input rounded-l-md text-sm text-muted-foreground font-mono">/</span>
                            <x-ui.input id="modal-shortcut" wire:model="shortcut" class="rounded-l-none"
                                placeholder="greet" />
                        </div>
                        @error('shortcut')
                            <span class="text-xs text-destructive">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid gap-2">
                        <x-ui.label for="modal-content">Response Content</x-ui.label>
                        <x-ui.textarea id="modal-content" wire:model="content" rows="5"
                            placeholder="Hi there, how can I help you today?" />
                        @error('content')
                            <span class="text-xs text-destructive">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid gap-2">
                        <x-ui.label for="modal-tags">Tags (optional)</x-ui.label>
                        <x-ui.input id="modal-tags" wire:model="tagsString"
                            placeholder="sales, support, billing (comma separated)" />
                        @error('tagsString')
                            <span class="text-xs text-destructive">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-start space-x-2.5 pt-1">
                        <input type="checkbox" id="modal-isShared" wire:model="isShared"
                            class="mt-0.5 rounded border-input size-4 accent-primary cursor-pointer" />
                        <div>
                            <label for="modal-isShared" class="text-sm font-medium cursor-pointer">Share with all
                                agents</label>
                            <p class="text-xs text-muted-foreground mt-0.5">When checked, this shortcut is available to
                                the whole team (global). Otherwise it's private to you.</p>
                        </div>
                    </div>
                </div>
                <x-ui.dialog-footer>
                    <x-ui.button type="button" variant="outline"
                        wire:click="$set('isModalOpen', false)">Cancel</x-ui.button>
                    <x-ui.button type="submit">
                        {{ $shortcutId ? 'Update Shortcut' : 'Add Shortcut' }}
                    </x-ui.button>
                </x-ui.dialog-footer>
            </form>
        </x-ui.dialog-content>
    </x-ui.dialog>
</div>
