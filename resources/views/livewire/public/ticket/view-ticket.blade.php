<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                #{{ $ticket->id }}: Chat
            </h2>
            <x-ui.badge variant="outline">{{ ucfirst($ticket->status) ?? "Open" }}</x-ui.badge>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @foreach($ticket->messages as $message)
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <x-ui.avatar>
                                    @if($message->is_ai_sender)
                                        <x-ui.avatar-fallback>AI</x-ui.avatar-fallback>
                                    @elseif($message->sender_type === App\Models\TenantUser::class)
                                        <x-ui.avatar-fallback>{{ substr($message->sender->user->name ?? "A", 0, 2) }}</x-ui.avatar-fallback>
                                    @else
                                        <x-ui.avatar-fallback>{{ substr($message->sender->name ?? "V", 0, 2) }}</x-ui.avatar-fallback>
                                    @endif
                                </x-ui.avatar>
                                <div>
                                    <p class="text-sm font-medium">
                                        @if($message->is_ai_sender)
                                            AI Assistant
                                        @elseif($message->sender_type === App\Models\TenantUser::class)
                                            {{ $message->sender->user->name ?? "Support Agent" }}
                                        @else
                                            {{ $message->sender->name ?? "You" }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-muted-foreground">{{ $message->created_at->format("M d, Y h:i A") }}</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="prose dark:prose-invert max-w-none">
                            {!! nl2br(e($message->body)) !!}
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            @endforeach

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Reply to Chat</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content>
                    <form wire:submit.prevent="addReply" class="space-y-4">
                        <div>
                            <textarea wire:model="replyBody" rows="4" placeholder="Type your reply here..." required class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                            @error("replyBody") <span class="text-destructive text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end">
                            <x-ui.button type="submit">Send Reply</x-ui.button>
                        </div>
                    </form>
                </x-ui.card-content>
            </x-ui.card>

        </div>
    </div>
</div>
