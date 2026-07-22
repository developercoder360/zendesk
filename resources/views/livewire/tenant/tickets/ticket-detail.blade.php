<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                #{{ $ticket->id }}: Chat from {{ $ticket->visitor->name ?? 'Unknown' }}
            </h2>
            <x-ui.button variant="outline" wire:navigate href="{{ route('tenant.tickets.index') }}">
                <x-lucide-arrow-left class="mr-2 size-4" /> Back to Tickets
            </x-ui.button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-6">

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
                                                    {{ $message->sender->user->name ?? "Unknown Agent" }}
                                                @else
                                                    {{ $message->sender->name ?? "Unknown Visitor" }}
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
                            <x-ui.card-title>Add a Reply</x-ui.card-title>
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
                
                <div class="space-y-6">
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>Ticket Properties</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div>
                                <x-ui.label for="status">Status</x-ui.label>
                                <x-ui.select id="status" wire:model.live="status">
                                    <x-ui.select-trigger>
                                        <x-ui.select-value placeholder="Select Status" />
                                    </x-ui.select-trigger>
                                    <x-ui.select-content>
                                        <x-ui.select-item value="">Select Status</x-ui.select-item>
                                        @foreach($statuses as $st)
                                            <x-ui.select-item value="{{ $st->id }}">{{ $st->name }}</x-ui.select-item>
                                        @endforeach
                                    </x-ui.select-content>
                                </x-ui.select>
                            </div>
                            <div>
                                <x-ui.label for="agent">Assignee</x-ui.label>
                                <x-ui.select id="agent" wire:model.live="assigned_agent_id">
                                    <x-ui.select-trigger>
                                        <x-ui.select-value placeholder="Unassigned" />
                                    </x-ui.select-trigger>
                                    <x-ui.select-content>
                                        <x-ui.select-item value="">Unassigned</x-ui.select-item>
                                        @foreach($agents as $agent)
                                            <x-ui.select-item value="{{ $agent->id }}">{{ $agent->user->name ?? 'Unknown' }}</x-ui.select-item>
                                        @endforeach
                                    </x-ui.select-content>
                                </x-ui.select>
                            </div>
                            <div>
                                <x-ui.label for="department">Department</x-ui.label>
                                <x-ui.select id="department" wire:model.live="department_id">
                                    <x-ui.select-trigger>
                                        <x-ui.select-value placeholder="None" />
                                    </x-ui.select-trigger>
                                    <x-ui.select-content>
                                        <x-ui.select-item value="">None</x-ui.select-item>
                                        @foreach($departments as $dept)
                                            <x-ui.select-item value="{{ $dept->id }}">{{ $dept->name }}</x-ui.select-item>
                                        @endforeach
                                    </x-ui.select-content>
                                </x-ui.select>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>

        </div>
    </div>
</div>
