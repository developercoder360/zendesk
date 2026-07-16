<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __("Tickets") }}
            </h2>
            <x-ui.button wire:navigate href="#">
                <x-lucide-plus class="mr-2 size-4" /> New Ticket
            </x-ui.button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>All Tickets</x-ui.card-title>
                    <x-ui.card-description>View and manage customer support requests.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="flex justify-between mb-4">
                        <div class="w-1/3">
                            <x-ui.input type="search" wire:model.live.debounce.300ms="search" placeholder="Search tickets..." />
                        </div>
                    </div>
                    
                    <div class="rounded-md border">
                        <x-ui.table>
                            <x-ui.table-header>
                                <x-ui.table-row>
                                    <x-ui.table-head class="w-[100px]">ID</x-ui.table-head>
                                    <x-ui.table-head>Subject</x-ui.table-head>
                                    <x-ui.table-head>Customer</x-ui.table-head>
                                    <x-ui.table-head>Status</x-ui.table-head>
                                    <x-ui.table-head>Priority</x-ui.table-head>
                                    <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                                </x-ui.table-row>
                            </x-ui.table-header>
                            <x-ui.table-body>
                                @forelse($tickets as $ticket)
                                    <x-ui.table-row>
                                        <x-ui.table-cell class="font-medium">#{{ $ticket->id }}</x-ui.table-cell>
                                        <x-ui.table-cell>{{ $ticket->subject }}</x-ui.table-cell>
                                        <x-ui.table-cell>{{ $ticket->user->name ?? "Unknown" }}</x-ui.table-cell>
                                        <x-ui.table-cell>
                                            <x-ui.badge variant="outline">{{ $ticket->status->name ?? "Open" }}</x-ui.badge>
                                        </x-ui.table-cell>
                                        <x-ui.table-cell>
                                            <x-ui.badge variant="secondary">{{ ucfirst($ticket->priority) }}</x-ui.badge>
                                        </x-ui.table-cell>
                                        <x-ui.table-cell class="text-right">
                                            <x-ui.button variant="ghost" size="sm" wire:navigate href="{{ route('tenant.tickets.show', $ticket) }}">
                                                View
                                            </x-ui.button>
                                        </x-ui.table-cell>
                                    </x-ui.table-row>
                                @empty
                                    <x-ui.table-row>
                                        <x-ui.table-cell colspan="6" class="h-24 text-center">
                                            No tickets found.
                                        </x-ui.table-cell>
                                    </x-ui.table-row>
                                @endforelse
                            </x-ui.table-body>
                        </x-ui.table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </div>
</div>
