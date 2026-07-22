<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                Tickets
            </h2>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <x-lucide-search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                    <x-ui.input type="search" placeholder="Search tickets..." class="pl-8 w-64" wire:model.live.debounce.300ms="search" />
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center bg-background p-4 rounded-lg border border-border">
                <div class="flex space-x-2">
                    <x-ui.button variant="{{ $currentTab === 'all' ? 'default' : 'ghost' }}" wire:click="setTab('all')">All Tickets</x-ui.button>
                    <x-ui.button variant="{{ $currentTab === 'my' ? 'default' : 'ghost' }}" wire:click="setTab('my')">My Tickets</x-ui.button>
                    <x-ui.button variant="{{ $currentTab === 'unassigned' ? 'default' : 'ghost' }}" wire:click="setTab('unassigned')">Unassigned</x-ui.button>
                </div>
                
                <div class="flex space-x-4">
                    <x-ui.select wire:model.live="filterStatus" class="w-40">
                        <x-ui.select-trigger>
                            <x-ui.select-value placeholder="All Statuses" />
                        </x-ui.select-trigger>
                        <x-ui.select-content>
                            <x-ui.select-item value="">All Statuses</x-ui.select-item>
                            @foreach($statuses as $status)
                                <x-ui.select-item value="{{ $status->id }}">{{ $status->name }}</x-ui.select-item>
                            @endforeach
                        </x-ui.select-content>
                    </x-ui.select>

                    <x-ui.select wire:model.live="filterAssignee" class="w-40">
                        <x-ui.select-trigger>
                            <x-ui.select-value placeholder="All Assignees" />
                        </x-ui.select-trigger>
                        <x-ui.select-content>
                            <x-ui.select-item value="">All Assignees</x-ui.select-item>
                            @foreach($agents as $agent)
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
                                <x-ui.table-head class="w-[100px]">ID</x-ui.table-head>
                                <x-ui.table-head>Subject</x-ui.table-head>
                                <x-ui.table-head>Customer</x-ui.table-head>
                                <x-ui.table-head>Assignee</x-ui.table-head>
                                <x-ui.table-head>Status</x-ui.table-head>
                                <x-ui.table-head>Priority</x-ui.table-head>
                                <x-ui.table-head class="text-right">Created</x-ui.table-head>
                            </x-ui.table-row>
                        </x-ui.table-header>
                        <x-ui.table-body>
                            @forelse ($tickets as $ticket)
                                <x-ui.table-row class="cursor-pointer hover:bg-muted/50 transition-colors" wire:click="navigateToTicket({{ $ticket->id }})" onclick="window.location.href='{{ route('tenant.tickets.show', $ticket) }}'">
                                    <x-ui.table-cell class="font-medium">#{{ $ticket->id }}</x-ui.table-cell>
                                    <x-ui.table-cell>Chat with {{ $ticket->visitor?->name ?? 'Unknown' }}</x-ui.table-cell>
                                    <x-ui.table-cell>
                                        {{ $ticket->visitor?->name ?? "Unknown" }}
                                    </x-ui.table-cell>
                                    <x-ui.table-cell>
                                        {{ $ticket->agent?->user?->name ?? "Unassigned" }}
                                    </x-ui.table-cell>
                                    <x-ui.table-cell>
                                        <x-ui.badge variant="outline">{{ ucfirst($ticket->status) ?? "Open" }}</x-ui.badge>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell>
                                        <span class="capitalize">Normal</span>
                                    </x-ui.table-cell>
                                    <x-ui.table-cell class="text-right">{{ $ticket->created_at->diffForHumans() }}</x-ui.table-cell>
                                </x-ui.table-row>
                            @empty
                                <x-ui.table-row>
                                    <x-ui.table-cell colspan="7" class="h-24 text-center">
                                        No tickets found.
                                    </x-ui.table-cell>
                                </x-ui.table-row>
                            @endforelse
                        </x-ui.table-body>
                    </x-ui.table>
                </div>
                @if($tickets->hasPages())
                    <div class="p-4 border-t border-border">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>
</div>
