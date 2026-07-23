<div class="h-screen w-screen overflow-hidden font-sans bg-background text-foreground flex flex-col">
    @if ($isNotConfigured)
        <!-- Unconfigured State (Allowed Domains Empty) -->
        <div class="h-full w-full flex items-center justify-center p-4 bg-muted/20">
            <x-ui.card class="w-full max-w-sm border border-amber-500/30 shadow-lg text-center bg-card">
                <x-ui.card-header class="pb-3">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 mb-3">
                        <x-lucide-settings class="size-6" />
                    </div>
                    <x-ui.card-title class="text-base font-bold text-foreground">Widget Not Activated</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-2 text-xs text-muted-foreground">
                    <p>{{ $statusMessage }}</p>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    @elseif ($isBlocked)
        <!-- Domain Restricted / Blocked State -->
        <div class="h-full w-full flex items-center justify-center p-4 bg-muted/20">
            <x-ui.card class="w-full max-w-sm border border-destructive/30 shadow-lg text-center bg-card">
                <x-ui.card-header class="pb-3">
                    <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-destructive/10 text-destructive mb-3">
                        <x-lucide-shield-alert class="size-6" />
                    </div>
                    <x-ui.card-title class="text-base font-bold text-foreground">Access Restricted</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="space-y-2 text-xs text-muted-foreground">
                    <p>{{ $statusMessage }}</p>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    @elseif ($mode === 'chat')
        <!-- LIVE CHAT MODE -->
        <div wire:poll.2s="syncChat" class="h-full w-full flex flex-col bg-card overflow-hidden">
            <!-- Header Bar -->
            <div class="shrink-0 px-4 py-3 text-white flex items-center justify-between shadow-md" style="background-color: {{ $primaryColor }};">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <div class="relative flex size-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full size-2.5 bg-emerald-400"></span>
                    </div>
                    <div class="truncate">
                        <h2 class="font-bold text-sm tracking-tight text-white truncate">Support Live Chat</h2>
                        <p class="text-[11px] text-white/80 truncate">{{ $welcomeText }}</p>
                    </div>
                </div>
                <button type="button" wire:click="toggleWidget" class="text-white/80 hover:text-white p-1 rounded-md hover:bg-white/10 transition-colors">
                    <x-lucide-x class="size-4" />
                </button>
            </div>

            <!-- Chat Transcript Messages Area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3.5 text-xs" id="chat-transcript-scroll">
                @if ($this->messages->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-center p-6 text-muted-foreground">
                        <div class="size-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-3">
                            <x-lucide-bot class="size-6" />
                        </div>
                        <p class="font-semibold text-sm text-foreground">Welcome to Live Support!</p>
                        <p class="text-xs mt-1 text-muted-foreground">Our 24/7 AI Assistant and support team are ready to help. Type your message below to get started.</p>
                    </div>
                @else
                    @foreach ($this->messages as $msg)
                        @if ($msg->sender_type === 'App\Models\Visitor')
                            <!-- Visitor Message (Right Aligned) -->
                            <div class="flex justify-end">
                                <div class="max-w-[85%] rounded-2xl px-3.5 py-2 text-white shadow-sm font-medium" style="background-color: {{ $primaryColor }};">
                                    <p class="whitespace-pre-line leading-relaxed">{{ $msg->body }}</p>
                                    <span class="block text-[10px] text-white/70 text-right mt-1 font-normal">{{ $msg->created_at->format('g:i A') }}</span>
                                </div>
                            </div>
                        @else
                            <!-- AI / Agent Message (Left Aligned) -->
                            <div class="flex justify-start items-start space-x-2">
                                <div class="size-7 rounded-full bg-neutral-200 dark:bg-neutral-800 flex items-center justify-center text-foreground shrink-0 mt-0.5 shadow-sm">
                                    @if ($msg->is_ai_sender)
                                        <x-lucide-bot class="size-4 text-indigo-500" />
                                    @else
                                        <x-lucide-user class="size-4 text-emerald-500" />
                                    @endif
                                </div>
                                <div class="max-w-[85%] bg-muted/60 border border-border/60 rounded-2xl px-3.5 py-2.5 text-foreground shadow-sm space-y-1">
                                    <div class="flex items-center space-x-1.5 font-bold text-[11px] text-foreground/90">
                                        @if ($msg->is_ai_sender)
                                            <span class="text-indigo-600 dark:text-indigo-400">AI Assistant</span>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">🤖 AI</span>
                                        @else
                                            <span>{{ $msg->sender->name ?? 'Support Agent' }}</span>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">Agent</span>
                                        @endif
                                    </div>
                                    <p class="whitespace-pre-line leading-relaxed text-xs">{{ $msg->body }}</p>
                                    <span class="block text-[10px] text-muted-foreground text-right mt-1 font-normal">{{ $msg->created_at->format('g:i A') }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- Typing Indicator -->
                @if ($isTyping)
                    <div class="flex justify-start items-center space-x-2 animate-fade-in">
                        <div class="size-7 rounded-full bg-neutral-200 dark:bg-neutral-800 flex items-center justify-center text-indigo-500 shrink-0">
                            <x-lucide-bot class="size-4" />
                        </div>
                        <div class="bg-muted/60 border border-border/60 rounded-2xl px-3.5 py-2.5 text-muted-foreground flex items-center space-x-1.5">
                            <span class="text-xs italic">AI Assistant is typing</span>
                            <span class="flex space-x-1 items-center">
                                <span class="size-1.5 bg-indigo-500 rounded-full animate-bounce"></span>
                                <span class="size-1.5 bg-indigo-500 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                                <span class="size-1.5 bg-indigo-500 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer Message Input Area -->
            <div class="p-3 border-t border-border bg-card shrink-0">
                <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                    <input 
                        type="text" 
                        wire:model="newMessageBody" 
                        placeholder="Type your message..." 
                        class="flex-1 rounded-xl bg-muted/40 border border-input px-3.5 py-2.5 text-xs text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/40"
                    />
                    <button 
                        type="submit" 
                        class="size-9 rounded-xl text-white flex items-center justify-center shrink-0 transition-opacity hover:opacity-90 active:scale-95 disabled:opacity-50"
                        style="background-color: {{ $primaryColor }};"
                        wire:loading.attr="disabled"
                    >
                        <x-lucide-send class="size-4" />
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- OFFLINE FORM MODE (Fallback when AI & Human Agents are both unavailable) -->
        <div class="h-full w-full flex flex-col bg-card overflow-hidden">
            <!-- Header Bar -->
            <div class="shrink-0 px-4 py-3 text-white flex items-center justify-between shadow-md" style="background-color: {{ $primaryColor }};">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <x-lucide-message-square class="size-5 shrink-0" />
                    <div class="truncate">
                        <h2 class="font-bold text-sm tracking-tight text-white truncate">Leave a Message</h2>
                        <p class="text-[11px] text-white/80 truncate">We are currently offline</p>
                    </div>
                </div>
                <button type="button" wire:click="toggleWidget" class="text-white/80 hover:text-white p-1 rounded-md hover:bg-white/10 transition-colors">
                    <x-lucide-x class="size-4" />
                </button>
            </div>

            <!-- Form Content -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 text-xs">
                <!-- Offline Banner -->
                <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-800 dark:text-amber-300 flex items-start space-x-2.5">
                    <x-lucide-clock class="size-4 shrink-0 text-amber-600 dark:text-amber-400 mt-0.5" />
                    <p class="leading-relaxed text-[11px]">
                        Our support team is currently offline and our AI assistant is unavailable. Please leave a message below and we will follow up with you via email as soon as possible.
                    </p>
                </div>

                @if ($formSubmitted)
                    <div class="p-6 text-center space-y-3">
                        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            <x-lucide-check-circle class="size-6" />
                        </div>
                        <h3 class="font-bold text-base text-foreground">Message Received!</h3>
                        <p class="text-xs text-muted-foreground">Thank you for contacting us. A support representative will review your message and reply via email.</p>
                        <button type="button" wire:click="$set('formSubmitted', false)" class="text-xs font-semibold text-primary hover:underline">
                            Send another message
                        </button>
                    </div>
                @else
                    <form wire:submit.prevent="submitOfflineForm" class="space-y-3">
                        <div>
                            <label class="block font-semibold mb-1 text-foreground">Your Name</label>
                            <input type="text" wire:model="name" placeholder="John Doe" class="w-full rounded-lg bg-muted/40 border border-input px-3 py-2 text-xs focus:ring-2 focus:ring-primary/40" />
                            @error('name') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold mb-1 text-foreground">Email Address</label>
                            <input type="email" wire:model="email" placeholder="john@example.com" class="w-full rounded-lg bg-muted/40 border border-input px-3 py-2 text-xs focus:ring-2 focus:ring-primary/40" />
                            @error('email') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold mb-1 text-foreground">Subject</label>
                            <input type="text" wire:model="subject" placeholder="How can we help?" class="w-full rounded-lg bg-muted/40 border border-input px-3 py-2 text-xs focus:ring-2 focus:ring-primary/40" />
                            @error('subject') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold mb-1 text-foreground">Message / Details</label>
                            <textarea wire:model="description" rows="3" placeholder="Describe your question or issue in detail..." class="w-full rounded-lg bg-muted/40 border border-input px-3 py-2 text-xs focus:ring-2 focus:ring-primary/40"></textarea>
                            @error('description') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="w-full py-2.5 rounded-xl font-bold text-white transition-opacity hover:opacity-90 shadow-sm"
                            style="background-color: {{ $primaryColor }};"
                        >
                            Send Message
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
