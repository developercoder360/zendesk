<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Customers</h1>
            <p class="text-sm text-muted-foreground">Directory of visitors and customers who have reached out via widget or tickets.</p>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="relative flex-1 max-w-sm">
            <x-ui.input 
                type="search" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search customers by name, email, or IP..." 
                class="pl-9"
            />
            <x-lucide-search class="size-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground pointer-events-none" />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Customers Table (7 or 12 cols depending on drawer) -->
        <div @class(['space-y-4', 'lg:col-span-7' => $selectedCustomerId, 'lg:col-span-12' => !$selectedCustomerId])>
            <x-ui.card>
                <x-ui.card-content class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-muted/50 text-xs font-semibold uppercase tracking-wider text-muted-foreground border-b border-border">
                                <tr>
                                    <th class="px-4 py-3">Customer</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Conversations</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Last Active</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @forelse($customers as $c)
                                    <tr @class([
                                        'hover:bg-muted/40 transition-colors cursor-pointer',
                                        'bg-primary/5 font-medium' => $selectedCustomerId === $c->id
                                    ]) wire:click="selectCustomer({{ $c->id }})">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-2.5">
                                                <div class="size-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ strtoupper(substr($c->name ?? 'G', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-foreground">{{ $c->name ?? 'Guest Visitor' }}</div>
                                                    <div class="text-[11px] text-muted-foreground font-mono">IP: {{ $c->ip_address ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-muted-foreground">
                                            {{ $c->email ?: 'Anonymous Visitor' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-muted text-foreground border border-input">
                                                <x-lucide-message-square class="size-3 mr-1 text-primary" />
                                                {{ $c->chats_count }} {{ Str::plural('chat', $c->chats_count) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            @if($c->banned_at)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-destructive/10 text-destructive border border-destructive/20">Banned</span>
                                            @elseif($c->status === 'online')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">Online</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-muted text-muted-foreground border border-input">Offline</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-muted-foreground">
                                            {{ $c->updated_at ? $c->updated_at->diffForHumans() : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <x-ui.button variant="ghost" size="xs" wire:click.stop="selectCustomer({{ $c->id }})">
                                                View History
                                                <x-lucide-chevron-right class="size-3.5 ml-1" />
                                            </x-ui.button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-xs text-muted-foreground">
                                            No customers found matching your search.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
            <div>
                {{ $customers->links() }}
            </div>
        </div>

        <!-- Customer Profile & Conversation History Detail Drawer (5 cols) -->
        @if ($selectedCustomer)
            <div class="lg:col-span-5 space-y-4">
                <x-ui.card class="sticky top-6">
                    <x-ui.card-header class="pb-3 border-b border-border">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="size-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($selectedCustomer->name ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <x-ui.card-title class="text-base">{{ $selectedCustomer->name ?? 'Guest Visitor' }}</x-ui.card-title>
                                    <x-ui.card-description class="text-xs">{{ $selectedCustomer->email ?: 'Anonymous Visitor' }}</x-ui.card-description>
                                </div>
                            </div>
                            <button type="button" wire:click="selectCustomer(null)" class="text-muted-foreground hover:text-foreground">
                                <x-lucide-x class="size-4" />
                            </button>
                        </div>
                    </x-ui.card-header>

                    <x-ui.card-content class="space-y-5 pt-4">
                        <!-- Profile Metadata Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs bg-muted/40 p-3 rounded-lg border border-border">
                            <div>
                                <span class="text-muted-foreground block text-[11px]">IP Address</span>
                                <span class="font-mono text-foreground font-medium">{{ $selectedCustomer->ip_address ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-muted-foreground block text-[11px]">Session ID</span>
                                <span class="font-mono text-foreground text-[10px] truncate block" title="{{ $selectedCustomer->session_id }}">{{ Str::limit($selectedCustomer->session_id, 14) }}</span>
                            </div>
                            <div>
                                <span class="text-muted-foreground block text-[11px]">First Seen</span>
                                <span class="text-foreground font-medium">{{ $selectedCustomer->created_at->format('M j, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-muted-foreground block text-[11px]">Last Activity</span>
                                <span class="text-foreground font-medium">{{ $selectedCustomer->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Conversation History Timeline -->
                        <div>
                            <h3 class="font-bold text-xs tracking-wide uppercase text-muted-foreground mb-3 flex items-center justify-between">
                                <span>Conversation History</span>
                                <span class="text-[11px] font-normal text-muted-foreground">{{ $selectedCustomer->chats->count() }} Total</span>
                            </h3>

                            <div class="space-y-3 max-h-[380px] overflow-y-auto pr-1">
                                @forelse($selectedCustomer->chats as $chat)
                                    <div class="p-3 rounded-lg border border-border bg-card hover:border-primary/40 transition-colors space-y-2">
                                        <div class="flex items-center justify-between text-xs">
                                            <div class="flex items-center space-x-2">
                                                <x-lucide-message-square class="size-3.5 text-primary" />
                                                <span class="font-semibold text-foreground">Chat #{{ $chat->id }}</span>
                                            </div>
                                            <span @class([
                                                'px-2 py-0.5 rounded text-[10px] font-semibold uppercase',
                                                'bg-emerald-500/10 text-emerald-600' => $chat->status === 'open',
                                                'bg-muted text-muted-foreground' => $chat->status !== 'open',
                                            ])>{{ $chat->status }}</span>
                                        </div>

                                        <div class="text-xs text-muted-foreground flex items-center justify-between text-[11px]">
                                            <span>{{ $chat->messages_count }} {{ Str::plural('message', $chat->messages_count) }}</span>
                                            <span>{{ $chat->created_at->format('M j, g:i A') }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-xs text-muted-foreground bg-muted/20 rounded-lg">
                                        No chat history found for this customer.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        @endif
    </div>
</div>
