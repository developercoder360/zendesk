<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            #{{ $ticket->id }}: {{ $ticket->subject }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <x-ui.card>
                <x-ui.card-header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <x-ui.avatar>
                                <x-ui.avatar-fallback>{{ substr($ticket->customer->name ?? "U", 0, 2) }}</x-ui.avatar-fallback>
                            </x-ui.avatar>
                            <div>
                                <p class="text-sm font-medium">{{ $ticket->customer->name ?? "You" }}</p>
                                <p class="text-xs text-muted-foreground">{{ $ticket->created_at->format("M d, Y h:i A") }}</p>
                            </div>
                        </div>
                        <x-ui.badge variant="outline">{{ $ticket->status->name ?? "Open" }}</x-ui.badge>
                    </div>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="prose dark:prose-invert max-w-none">
                        {{ $ticket->description }}
                    </div>
                </x-ui.card-content>
            </x-ui.card>

            @foreach($ticket->replies as $reply)
                @if(!$reply->is_internal)
                <x-ui.card>
                    <x-ui.card-header>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <x-ui.avatar>
                                    <x-ui.avatar-fallback>
                                        {{ $reply->customer_id ? substr($reply->customer->name ?? "U", 0, 2) : substr($reply->user->name ?? "A", 0, 2) }}
                                    </x-ui.avatar-fallback>
                                </x-ui.avatar>
                                <div>
                                    <p class="text-sm font-medium">
                                        {{ $reply->customer_id ? ($reply->customer->name ?? "You") : ($reply->user->name ?? "Support Agent") }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">{{ $reply->created_at->format("M d, Y h:i A") }}</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="prose dark:prose-invert max-w-none">
                            {!! nl2br(e($reply->body)) !!}
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
                @endif
            @endforeach

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Reply to Ticket</x-ui.card-title>
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
