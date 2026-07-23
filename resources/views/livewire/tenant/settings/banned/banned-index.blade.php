<div>
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Banned Visitors</span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Banned Visitors & IPs</h1>
            <p class="text-sm text-muted-foreground">Manage blocked IP addresses and visitors.</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <x-lucide-search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <x-ui.input type="search" placeholder="Search IP or reason..." class="pl-8 w-64" wire:model.live.debounce.300ms="search" />
            </div>
            <x-ui.button wire:click="openAddBanModal">Ban New IP</x-ui.button>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-ui.card>
                <div class="overflow-x-auto">
                    <x-ui.table>
                        <x-ui.table-header>
                            <x-ui.table-row>
                                <x-ui.table-head>IP Address / Visitor</x-ui.table-head>
                                <x-ui.table-head>Reason</x-ui.table-head>
                                <x-ui.table-head>Banned Date</x-ui.table-head>
                                <x-ui.table-head class="text-right">Action</x-ui.table-head>
                            </x-ui.table-row>
                        </x-ui.table-header>
                        <x-ui.table-body>
                            @forelse ($bannedVisitors as $item)
                                <x-ui.table-row>
                                    <x-ui.table-cell>
                                        <div class="font-medium font-mono text-sm">{{ $item->ip_address ?? 'N/A' }}</div>
                                        @if($item->email || $item->name)
                                            <div class="text-xs text-muted-foreground">{{ $item->name }} ({{ $item->email ?? 'No email' }})</div>
                                        @endif
                                    </x-ui.table-cell>
                                    <x-ui.table-cell>
                                        {{ $item->ban_reason ?: 'No reason provided' }}
                                    </x-ui.table-cell>
                                    <x-ui.table-cell>
                                        <span class="text-xs text-muted-foreground">
                                            {{ $item->banned_at ? $item->banned_at->format('M d, Y h:i A') : 'N/A' }}
                                        </span>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="text-right">
                                        <x-ui.button variant="outline" size="sm" wire:click="unban('{{ $item->id }}')">
                                            Unban
                                        </x-ui.button>
                                    </x-ui.table-cell>
                                </x-ui.table-row>
                            @empty
                                <x-ui.table-row>
                                    <x-ui.table-cell colspan="4" class="h-24 text-center text-muted-foreground">
                                        No banned visitors or IPs found.
                                    </x-ui.table-cell>
                                </x-ui.table-row>
                            @endforelse
                        </x-ui.table-body>
                    </x-ui.table>
                </div>
                @if($bannedVisitors->hasPages())
                    <div class="p-4 border-t border-border">
                        {{ $bannedVisitors->links() }}
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>

    <!-- Modal -->
    <x-ui.dialog wire:model="isModalOpen">
        <x-ui.dialog-content>
            <x-ui.dialog-header>
                <x-ui.dialog-title>Ban IP Address</x-ui.dialog-title>
                <x-ui.dialog-description>
                    Prevent requests and chat widget connections originating from this IP address.
                </x-ui.dialog-description>
            </x-ui.dialog-header>

            <form wire:submit="saveBan">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <x-ui.label for="ip_address">IP Address *</x-ui.label>
                        <x-ui.input id="ip_address" wire:model="ip_address" placeholder="e.g. 192.168.1.1" required />
                        @error('ip_address') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid gap-2">
                        <x-ui.label for="ban_reason">Reason for Ban (Optional)</x-ui.label>
                        <x-ui.textarea id="ban_reason" wire:model="ban_reason" rows="3" placeholder="Abusive behavior, spamming tickets..." />
                        @error('ban_reason') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <x-ui.dialog-footer>
                    <x-ui.button type="button" variant="outline" wire:click="$set('isModalOpen', false)">Cancel</x-ui.button>
                    <x-ui.button type="submit" variant="destructive">Ban IP</x-ui.button>
                </x-ui.dialog-footer>
            </form>
        </x-ui.dialog-content>
    </x-ui.dialog>
</div>
