<div>
    @if($isOpen && $visitor)
        <!-- Floating Visitor Chat Drawer -->
        <div wire:poll.3s="syncActiveChat" class="fixed bottom-4 right-4 z-50 w-[730px] max-w-[calc(100vw-2rem)] shadow-2xl rounded-t-lg border border-border/80 overflow-hidden bg-background font-sans text-xs transition-all duration-200">
            
            <!-- Dark Header Bar -->
            <div class="bg-[#2a2e33] text-white px-3 py-2 flex items-center justify-between select-none">
                <!-- Left: Status Dot, Avatar & Visitor Details -->
                <div class="flex items-center space-x-2 min-w-0">
                    <!-- Green Avatar Square Icon -->
                    <div class="size-6 rounded bg-[#44b600] flex items-center justify-center text-white shrink-0">
                        <x-lucide-user class="size-4" />
                    </div>
                    
                    <!-- Visitor Title -->
                    <span class="font-bold text-sm tracking-tight text-white truncate max-w-[180px]">
                        {{ $visitor->name ?: 'Visitor ' . $visitor->id }}
                    </span>
                    
                    <!-- Badges / Icons: Flag, OS, Browser -->
                    <div class="flex items-center space-x-1.5 ml-1 text-neutral-300 shrink-0">
                        @if($visitor->country_code)
                            <span class="text-xs uppercase font-medium bg-neutral-700 px-1 rounded text-neutral-200" title="{{ $visitor->country_code }}">
                                {{ $visitor->country_code }}
                            </span>
                        @endif

                        @if(($visitor->device_type ?? 'desktop') === 'mobile')
                            <x-lucide-smartphone class="size-3.5 text-neutral-300" title="Mobile" />
                        @elseif(($visitor->device_type ?? 'desktop') === 'tablet')
                            <x-lucide-tablet class="size-3.5 text-neutral-300" title="Tablet" />
                        @else
                            <x-lucide-monitor class="size-3.5 text-neutral-300" title="Desktop" />
                        @endif

                        <x-lucide-globe class="size-3.5 text-neutral-300" title="{{ $visitor->browser ?? 'Browser' }}" />
                    </div>
                </div>

                <!-- Right: Actions Dropdown, Minimize, Close -->
                <div class="flex items-center space-x-1.5 shrink-0 ml-2">
                    <!-- Actions Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="bg-neutral-600 hover:bg-neutral-500 text-white text-xs px-2.5 py-1 rounded flex items-center gap-1 font-medium transition-colors">
                            <span>Actions</span>
                            <x-lucide-chevron-down class="size-3" />
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-1 w-36 bg-background border border-border text-foreground rounded shadow-lg z-50 py-1 text-xs">
                            <button type="button" class="w-full text-left px-3 py-1.5 hover:bg-muted transition-colors text-destructive">Ban visitor</button>
                            <button type="button" class="w-full text-left px-3 py-1.5 hover:bg-muted transition-colors">End chat</button>
                        </div>
                    </div>

                    <!-- Minimize Button -->
                    <button type="button" wire:click="toggleMinimize" class="text-neutral-300 hover:text-white p-1 rounded hover:bg-neutral-700 transition-colors" title="Minimize">
                        <x-lucide-minus class="size-4" />
                    </button>

                    <!-- Close Button -->
                    <button type="button" wire:click="closeDrawer" class="text-neutral-300 hover:text-white p-1 rounded hover:bg-neutral-700 transition-colors" title="Close">
                        <x-lucide-x class="size-4" />
                    </button>
                </div>
            </div>

            @if(!$isMinimized)
                <!-- Sub-header / Multi-Visitor Tab Switcher Bar -->
                <div class="bg-neutral-100 dark:bg-neutral-900 border-b px-3 pt-2 flex items-center justify-between text-xs overflow-x-auto">
                    <div class="flex items-center space-x-1">
                        @foreach($activeChats as $chatItem)
                            @php
                                $isCurrent = $chatItem->visitor_id === $activeVisitorId;
                                $vName = $chatItem->visitor?->name ?: ('Visitor ' . $chatItem->visitor_id);
                            @endphp
                            <button type="button" 
                                    wire:click="selectVisitor({{ $chatItem->visitor_id }}, {{ $chatItem->id }})"
                                    class="{{ $isCurrent ? 'bg-background text-foreground font-semibold border-t border-x border-border shadow-xs' : 'text-muted-foreground hover:text-foreground font-medium' }} px-3 py-1.5 rounded-t transition-colors flex items-center gap-1.5 shrink-0">
                                <span class="size-2 rounded-full {{ ($chatItem->visitor?->status ?? 'online') === 'online' ? 'bg-[#44b600]' : 'bg-muted-foreground/50' }}"></span>
                                <span class="truncate max-w-[110px]">{{ $vName }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Main Drawer Panel (Split Layout) -->
                <div class="grid grid-cols-12 divide-x divide-border h-[480px]">
                    
                    <!-- Left Column: Chat Transcript & Input -->
                    <div class="col-span-7 flex flex-col bg-background h-full min-h-0">
                        
                        <!-- Chat Transcript Area -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-3 text-xs">
                            
                            @if($activeChat?->needs_human_escalation)
                                <div class="rounded-md bg-amber-500/10 border border-amber-500/20 p-2.5 mb-3 flex items-center gap-2 text-amber-600 dark:text-amber-400">
                                    <x-lucide-alert-triangle class="size-4 shrink-0" />
                                    <span><strong>Human Escalation Triggered:</strong> Visitor requested human support or escalation.</span>
                                </div>
                            @endif

                            @forelse($messages as $msg)
                                @php
                                    $senderName = 'System';
                                    if ($msg->sender_type === 'App\Models\TenantUser') {
                                        $senderName = $msg->sender?->user?->name ?? 'Agent';
                                    } elseif ($msg->sender_type === 'App\Models\Visitor') {
                                        $senderName = $msg->sender?->name ?: ('Visitor ' . $msg->sender_id);
                                    } elseif ($msg->getIsAiSenderAttribute()) {
                                        $senderName = 'AI Assistant 🤖';
                                    }
                                    $isAgentMsg = $msg->sender_type === 'App\Models\TenantUser';
                                    $isAiMsg = $msg->getIsAiSenderAttribute();
                                @endphp

                                <div class="space-y-1">
                                    <div class="flex items-center justify-between font-semibold {{ $isAgentMsg ? 'text-primary' : ($isAiMsg ? 'text-purple-600 dark:text-purple-400' : 'text-foreground') }}">
                                        <span>{{ $senderName }}</span>
                                        <span class="font-normal text-muted-foreground/70 text-[11px]">
                                            {{ $msg->created_at ? $msg->created_at->format('g:i A') : '' }}
                                        </span>
                                    </div>
                                    <div class="text-foreground/90 flex items-end justify-between">
                                        <p class="leading-relaxed whitespace-pre-wrap">{{ $msg->body }}</p>
                                        @if($isAgentMsg && $loop->last)
                                            <x-lucide-check-check class="size-4 text-primary shrink-0 ml-2 mb-0.5" />
                                        @endif
                                    </div>
                                </div>

                                @if(!$loop->last)
                                    <div class="border-b border-dashed border-border/60 my-1.5"></div>
                                @endif
                            @empty
                                <div class="text-center py-12 text-muted-foreground">
                                    <x-lucide-message-square class="size-8 mx-auto mb-2 opacity-50" />
                                    <p>No messages in this chat session yet.</p>
                                </div>
                            @endforelse

                            @if($activeChat?->is_typing)
                                <div class="flex items-center space-x-2 text-purple-600 dark:text-purple-400 py-2 animate-pulse font-medium">
                                    <x-lucide-bot class="size-4" />
                                    <span>AI Assistant is typing...</span>
                                </div>
                            @endif

                        </div>

                        <!-- Chat Message Input Area -->
                        <div class="p-3 border-t border-border bg-background shrink-0">
                            <form wire:submit="sendMessage">
                                <div class="relative flex flex-col gap-2">
                                    <textarea rows="2" 
                                              wire:model="newMessageBody"
                                              @keydown.enter.prevent="$wire.sendMessage()"
                                              placeholder="Type your message and press Enter..."
                                              class="w-full text-xs border border-border rounded-md p-2.5 bg-background focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-muted-foreground/60 resize-none"></textarea>
                                    <div class="flex justify-end">
                                        <x-ui.button type="submit" size="sm" class="h-7 text-xs">
                                            <x-lucide-send class="size-3 mr-1" />
                                            Send
                                        </x-ui.button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                    <!-- Right Column: Visitor Info Sidebar -->
                    <div class="col-span-5 flex flex-col bg-muted/30 h-full min-h-0 p-3.5 space-y-3 overflow-y-auto">
                        
                        <!-- Top Avatar & Name/Email Inputs -->
                        <div class="flex items-start space-x-2.5">
                            <!-- Green Avatar Box -->
                            <div class="size-11 rounded bg-[#44b600] flex items-center justify-center text-white shrink-0 mt-0.5 shadow-xs">
                                <x-lucide-user class="size-7 text-white" />
                            </div>
                            <!-- Stacked Inputs -->
                            <div class="flex-1 space-y-1.5">
                                <input type="text" 
                                       wire:model.blur="visitorName" 
                                       placeholder="Add name" 
                                       class="w-full h-7 text-xs border border-border rounded px-2.5 bg-background focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-muted-foreground/60" />
                                <input type="email" 
                                       wire:model.blur="visitorEmail" 
                                       placeholder="Add email" 
                                       class="w-full h-7 text-xs border border-border rounded px-2.5 bg-background focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-muted-foreground/60" />
                            </div>
                        </div>

                        <!-- Phone Input -->
                        <div>
                            <input type="text" 
                                   wire:model.blur="visitorPhone" 
                                   placeholder="Add phone number" 
                                   class="w-full h-7 text-xs border border-border rounded px-2.5 bg-background focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-muted-foreground/60" />
                        </div>

                        <!-- Notes Textarea -->
                        <div>
                            <textarea rows="2" 
                                      wire:model.blur="visitorNotes" 
                                      placeholder="Add visitor notes" 
                                      class="w-full text-xs border border-border rounded p-2 bg-background focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-muted-foreground/60 resize-none"></textarea>
                        </div>

                        <!-- Tags Section -->
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs text-muted-foreground font-medium">
                                <span>Tags</span>
                                <button type="button" title="Tags help organize and filter chats">
                                    <x-lucide-help-circle class="size-3.5 text-muted-foreground/70 hover:text-foreground transition-colors" />
                                </button>
                            </div>
                            <input type="text" 
                                   wire:model.blur="chatTags" 
                                   placeholder="Add chat tags (comma separated)" 
                                   class="w-full h-7 text-xs border border-border rounded px-2.5 bg-background focus:outline-none focus:ring-1 focus:ring-primary placeholder:text-muted-foreground/60" />
                        </div>

                        <!-- Stats Grid Box (3 columns) -->
                        <div class="border border-border rounded-md bg-background grid grid-cols-3 divide-x divide-border p-2 text-center my-1">
                            <div class="px-1">
                                <div class="font-bold text-sm text-foreground">{{ $visitor->visits_count ?? 1 }}</div>
                                <div class="text-[10px] text-muted-foreground leading-tight mt-0.5">Past visits</div>
                            </div>
                            <div class="px-1">
                                <div class="font-bold text-sm text-foreground">{{ $pastChatsCount }}</div>
                                <div class="text-[10px] text-muted-foreground leading-tight mt-0.5">Past chats</div>
                            </div>
                            <div class="px-1">
                                @php
                                    $timeOnSite = '1 min';
                                    if ($visitor->first_seen_at) {
                                        $mins = max(1, (int) $visitor->first_seen_at->diffInMinutes(now()));
                                        $timeOnSite = $mins . ' mins';
                                    }
                                @endphp
                                <div class="font-bold text-sm text-foreground">{{ $timeOnSite }}</div>
                                <div class="text-[10px] text-muted-foreground leading-tight mt-0.5">Time on site</div>
                            </div>
                        </div>

                        <!-- Visitor Path Section -->
                        <div class="space-y-1.5 pt-1">
                            <div class="text-xs font-medium text-muted-foreground">Visitor path</div>
                            <div class="space-y-1 text-xs">
                                <div class="flex items-center space-x-1.5 text-foreground/80">
                                    <x-lucide-arrow-down-circle class="size-3.5 text-muted-foreground shrink-0" />
                                    <span class="truncate">{{ $visitor->referrer ?: 'Direct / Search' }}</span>
                                </div>
                                <div class="flex items-center space-x-1.5 text-foreground/80">
                                    <x-lucide-disc class="size-3.5 text-muted-foreground shrink-0" />
                                    <span class="truncate" title="{{ $visitor->current_page_title ?: $visitor->current_page_url }}">
                                        {{ $visitor->current_page_title ?: ($visitor->current_page_url ?: 'Home Page') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Action Buttons -->
                        <div class="grid grid-cols-2 gap-2 pt-2 mt-auto">
                            <a href="{{ route('tenant.tickets.index') }}" class="w-full py-1.5 px-2 text-xs font-medium border border-border rounded bg-background hover:bg-muted/70 transition-colors text-center text-foreground block">
                                Create ticket
                            </a>
                            <a href="{{ route('tenant.visitors.index') }}" class="w-full py-1.5 px-2 text-xs font-medium border border-border rounded bg-background hover:bg-muted/70 transition-colors text-center text-foreground block">
                                View profile
                            </a>
                        </div>

                    </div>

                </div>
            @endif

        </div>
    @elseif($isOpen && !$visitor)
        <!-- Open Drawer Placeholder if Loading -->
        <div wire:poll.3s="syncActiveChat"></div>
    @else
        <!-- Closed Drawer Lightweight Polling Element -->
        <div wire:poll.30s="checkActiveVisitorSession">
            @if(count($activeChats ?? []) > 0)
                <button type="button" 
                        wire:click="openDrawer" 
                        class="fixed bottom-4 right-4 z-50 bg-[#2a2e33] hover:bg-neutral-800 text-white px-4 py-2.5 rounded-full shadow-2xl border border-neutral-700 flex items-center space-x-2 text-xs font-semibold transition-all">
                    <span class="size-2 rounded-full bg-[#44b600]"></span>
                    <span>Active Visitor Chats ({{ count($activeChats) }})</span>
                    <x-lucide-message-square class="size-4 ml-1" />
                </button>
            @endif
        </div>
    @endif
</div>
