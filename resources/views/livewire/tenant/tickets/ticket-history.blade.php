<div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer" wire:navigate
            href="{{ route('tenant.tickets.index') }}">Tickets</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">History</span>
    </nav>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Ticket History</h1>
            <p class="text-sm text-muted-foreground">Review past customer interactions and closed tickets.</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <x-lucide-search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <x-ui.input type="search" placeholder="Search history..." class="pl-8 w-64"
                    wire:model.live.debounce.300ms="search" />
            </div>
            <x-ui.select wire:model.live="filterAssignee" class="w-48">
                <x-ui.select-trigger>
                    <x-ui.select-value placeholder="All Agents" />
                </x-ui.select-trigger>
                <x-ui.select-content>
                    <x-ui.select-item value="">All Agents</x-ui.select-item>
                    @foreach ($agents as $agent)
                        <x-ui.select-item value="{{ $agent->id }}">{{ $agent->name }}</x-ui.select-item>
                    @endforeach
                </x-ui.select-content>
            </x-ui.select>
        </div>
    </div>
    <x-ui.card>
        <div class="overflow-x-auto">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Ticket</x-ui.table-head>
                        <x-ui.table-head>Customer</x-ui.table-head>
                        <x-ui.table-head>Assignee</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Last Updated</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($tickets as $ticket)
                        <x-ui.table-row class="cursor-pointer hover:bg-muted/50"
                            wire:click="$navigate('{{ route('tenant.tickets.show', $ticket) }}')">
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">
                                    #{{ $ticket->id }}
                                </div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    {{ $ticket->department?->name ?? 'No Department' }}
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                {{ $ticket->visitor?->name ?? 'Unknown' }}
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if ($ticket->agent && $ticket->agent->user)
                                    <div class="flex items-center gap-2">
                                        <x-ui.avatar class="h-6 w-6">
                                            <x-ui.avatar-fallback
                                                class="text-xs">{{ substr($ticket->agent->user->name, 0, 2) }}</x-ui.avatar-fallback>
                                        </x-ui.avatar>
                                        <span class="text-sm">{{ $ticket->agent->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted-foreground italic text-sm">Unassigned</span>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="secondary">
                                    {{ ucfirst($ticket->status) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-sm text-muted-foreground">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="5" class="h-24 text-center">
                                No tickets found in history.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </div>
        @if ($tickets->hasPages())
            <div class="p-4 border-t border-border">
                {{ $tickets->links() }}
            </div>
        @endif
    </x-ui.card>
</div>
