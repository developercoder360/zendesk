<div>
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="font-medium text-foreground">Visitors</span>
    </nav>
    <div class="flex items-center justify-between mb-6  ">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Visitors</h1>
            <p class="text-sm text-muted-foreground">Monitor and interact with current site visitors.</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <x-lucide-search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <x-ui.input type="search" placeholder="Search visitors, IP, referrer..." class="pl-8 w-72"
                    wire:model.live.debounce.300ms="search" />
            </div>
        </div>
    </div>
    <x-ui.card>
        <div class="overflow-x-auto">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head class="w-16">Status</x-ui.table-head>
                        <x-ui.table-head>Visitor</x-ui.table-head>
                        <x-ui.table-head>Served By</x-ui.table-head>
                        <x-ui.table-head>Currently Viewing</x-ui.table-head>
                        <x-ui.table-head>Referrer</x-ui.table-head>
                        <x-ui.table-head>Online</x-ui.table-head>
                        <x-ui.table-head class="text-center">Visits / Chats</x-ui.table-head>
                        <x-ui.table-head class="text-right">Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse ($visitors as $visitor)
                        <x-ui.table-row class="hover:bg-muted/40 transition-colors">
                            <!-- Status / Country / Device -->
                            <x-ui.table-cell>
                                <div class="flex items-center space-x-2">
                                    <!-- Status Dot -->
                                    @if ($visitor->is_banned)
                                        <span class="size-2.5 rounded-full bg-destructive shrink-0"
                                            title="Banned"></span>
                                    @elseif(($visitor->status ?? 'online') === 'online')
                                        <span class="size-2.5 rounded-full bg-primary shrink-0" title="Online"></span>
                                    @elseif(($visitor->status ?? 'online') === 'idle')
                                        <span class="size-2.5 rounded-full bg-amber-400 shrink-0" title="Idle"></span>
                                    @else
                                        <span class="size-2.5 rounded-full bg-muted-foreground/40 shrink-0"
                                            title="Offline"></span>
                                    @endif
                                    <!-- Device Icon -->
                                    @if (($visitor->device_type ?? 'desktop') === 'mobile')
                                        <x-lucide-smartphone class="size-4 text-muted-foreground" title="Mobile" />
                                    @elseif(($visitor->device_type ?? 'desktop') === 'tablet')
                                        <x-lucide-tablet class="size-4 text-muted-foreground" title="Tablet" />
                                    @else
                                        <x-lucide-monitor class="size-4 text-muted-foreground" title="Desktop" />
                                    @endif
                                    <!-- Country Flag Code -->
                                    @if ($visitor->country_code)
                                        <span
                                            class="text-xs font-semibold px-1 py-0.5 rounded bg-muted text-muted-foreground uppercase"
                                            title="Country: {{ $visitor->country_code }}">
                                            {{ $visitor->country_code }}
                                        </span>
                                    @endif
                                </div>
                            </x-ui.table-cell>
                            <!-- Visitor Info -->
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $visitor->name ?: 'Anonymous Visitor' }}
                                </div>
                                <div class="text-xs text-muted-foreground flex items-center space-x-2 mt-0.5">
                                    <span>{{ $visitor->email ?: 'No email' }}</span>
                                    <span>•</span>
                                    <code
                                        class="font-mono text-[11px] text-muted-foreground/80">{{ $visitor->ip_address ?: 'Unknown IP' }}</code>
                                </div>
                            </x-ui.table-cell>
                            <!-- Served By Agent -->
                            <x-ui.table-cell>
                                @if ($visitor->servedByAgent && $visitor->servedByAgent->user)
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="size-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">
                                            {{ substr($visitor->servedByAgent->user->name, 0, 1) }}
                                        </div>
                                        <span
                                            class="text-xs font-medium">{{ $visitor->servedByAgent->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-muted-foreground italic">Unserved</span>
                                @endif
                            </x-ui.table-cell>
                            <!-- Viewing Page + Open Tabs Badge -->
                            <x-ui.table-cell class="max-w-xs">
                                <div class="flex items-center space-x-1.5">
                                    <!-- Open Tabs Badge -->
                                    <span
                                        class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold rounded-full bg-primary/10 text-primary shrink-0"
                                        title="{{ $visitor->open_tabs_count ?? 1 }} open tab(s)">
                                        {{ $visitor->open_tabs_count ?? 1 }}
                                    </span>
                                    <!-- Page Title / URL -->
                                    <div class="truncate text-xs">
                                        <div class="font-medium truncate text-foreground"
                                            title="{{ $visitor->current_page_title ?: 'Viewing site' }}">
                                            {{ $visitor->current_page_title ?: ($visitor->current_page_url ?: 'Home Page') }}
                                        </div>
                                        @if ($visitor->current_page_url)
                                            <a href="{{ $visitor->current_page_url }}" target="_blank"
                                                class="text-[11px] text-muted-foreground hover:underline truncate block">
                                                {{ Str::limit($visitor->current_page_url, 40) }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </x-ui.table-cell>
                            <!-- Referrer -->
                            <x-ui.table-cell>
                                <span class="text-xs text-muted-foreground">
                                    {{ $visitor->referrer ? Str::limit(str_replace(['http://', 'https://'], '', $visitor->referrer), 25) : 'Direct' }}
                                </span>
                            </x-ui.table-cell>
                            <!-- Online Duration -->
                            <x-ui.table-cell>
                                <div class="text-xs font-medium">
                                    @if ($visitor->first_seen_at)
                                        {{ (int) $visitor->first_seen_at->diffInMinutes(now()) }} mins
                                    @else
                                        Just now
                                    @endif
                                </div>
                                <div class="text-[11px] text-muted-foreground">
                                    {{ $visitor->last_seen_at ? $visitor->last_seen_at->diffForHumans() : '' }}
                                </div>
                            </x-ui.table-cell>
                            <!-- Visits & Chats Count -->
                            <x-ui.table-cell class="text-center">
                                <div class="inline-flex items-center space-x-2 text-xs">
                                    <span class="px-2 py-0.5 rounded bg-muted text-muted-foreground font-medium"
                                        title="Visits count">
                                        {{ $visitor->visits_count ?? 1 }} visits
                                    </span>
                                    <span class="px-2 py-0.5 rounded bg-primary/10 text-primary font-medium"
                                        title="Chats count">
                                        {{ $visitor->chats->count() }} chats
                                    </span>
                                </div>
                            </x-ui.table-cell>
                            <!-- Actions -->
                            <x-ui.table-cell class="text-right">
                                @if ($visitor->is_banned)
                                    <x-ui.button variant="outline" size="xs"
                                        wire:click="unbanVisitor('{{ $visitor->id }}')">
                                        Unban
                                    </x-ui.button>
                                @else
                                    <x-ui.button variant="destructive" size="xs"
                                        wire:click="openBanModal('{{ $visitor->id }}')">
                                        Ban
                                    </x-ui.button>
                                @endif
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="8" class="h-24 text-center text-muted-foreground">
                                No active visitors logged.
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </div>
        @if ($visitors->hasPages())
            <div class="p-4 border-t border-border">
                {{ $visitors->links() }}
            </div>
        @endif
    </x-ui.card>
    <!-- Ban Modal -->
    <x-ui.dialog wire:model="banModalOpen">
        <x-ui.dialog-content>
            <x-ui.dialog-header>
                <x-ui.dialog-title>Ban Visitor</x-ui.dialog-title>
                <x-ui.dialog-description>
                    Are you sure you want to ban this visitor? They will no longer be able to access the chat widget or
                    submit tickets.
                </x-ui.dialog-description>
            </x-ui.dialog-header>
            <div class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <x-ui.label for="banReason">Reason for ban (optional)</x-ui.label>
                    <x-ui.input id="banReason" wire:model="banReason" placeholder="Spam, abusive behavior, etc." />
                </div>
            </div>
            <x-ui.dialog-footer>
                <x-ui.button variant="outline" wire:click="$set('banModalOpen', false)">Cancel</x-ui.button>
                <x-ui.button variant="destructive" wire:click="banVisitor">Ban Visitor</x-ui.button>
            </x-ui.dialog-footer>
        </x-ui.dialog-content>
    </x-ui.dialog>
</div>
