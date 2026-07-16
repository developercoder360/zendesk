<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                #{{ $ticket->id }}: {{ $ticket->subject }}
            </h2>
            <x-ui.button variant="outline" wire:navigate href="{{ route('tenant.tickets.index') }}">
                <x-lucide-arrow-left class="mr-2 size-4" /> Back to Tickets
            </x-ui.button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <x-ui.avatar>
                                        <x-ui.avatar-fallback>{{ substr($ticket->user->name ?? "U", 0, 2) }}</x-ui.avatar-fallback>
                                    </x-ui.avatar>
                                    <div>
                                        <p class="text-sm font-medium">{{ $ticket->user->name ?? "Unknown Customer" }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $ticket->created_at->format("M d, Y h:i A") }}</p>
                                    </div>
                                </div>
                            </div>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="prose dark:prose-invert max-w-none">
                                {{ $ticket->description }}
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>

                    @foreach($ticket->replies as $reply)
                        <x-ui.card class="{{ $reply->is_internal ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                            <x-ui.card-header>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <x-ui.avatar>
                                            <x-ui.avatar-fallback>{{ substr($reply->user->name ?? "U", 0, 2) }}</x-ui.avatar-fallback>
                                        </x-ui.avatar>
                                        <div>
                                            <p class="text-sm font-medium">
                                                {{ $reply->user->name ?? "Unknown User" }}
                                                @if($reply->is_internal)
                                                    <x-ui.badge variant="secondary" class="ml-2 text-xs bg-yellow-100 text-yellow-800">Internal Note</x-ui.badge>
                                                @endif
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
                    @endforeach

                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Add a Reply</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <form wire:submit.prevent="addReply" class="space-y-4">
                                <div>
                                    <textarea wire:model="replyBody" rows="4" placeholder="Type your reply here..." required class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                                    @error("replyBody") <span class="text-destructive text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center space-x-2">
                                    <x-ui.checkbox id="isInternal" wire:model="isInternal" />
                                    <x-ui.label for="isInternal">Internal Note (Hidden from customer)</x-ui.label>
                                </div>
                                <div class="flex justify-end">
                                    <x-ui.button type="submit">Send Reply</x-ui.button>
                                </div>
                            </form>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
                
                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Ticket Properties</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Status</p>
                                <p>{{ $ticket->status->name ?? "Open" }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Priority</p>
                                <p class="capitalize">{{ $ticket->priority }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Assignee</p>
                                <p>{{ $ticket->agent->name ?? "Unassigned" }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Department</p>
                                <p>{{ $ticket->department->name ?? "None" }}</p>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

        </div>
    </div>
</div>
