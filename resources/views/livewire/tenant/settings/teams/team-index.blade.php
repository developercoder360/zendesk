<div>
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Teams</span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Teams</h1>
            <p class="text-sm text-muted-foreground">Group support agents into functional teams for ticket routing and workload management.</p>
        </div>
        <x-ui.button wire:click="openCreateModal">
            <x-lucide-plus class="size-4 mr-1.5" />
            Create Team
        </x-ui.button>
    </div>

    <!-- Teams Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teams as $team)
            <x-ui.card class="flex flex-col justify-between hover:border-primary/40 transition-colors shadow-sm">
                <x-ui.card-header class="pb-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2.5">
                            <div class="size-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                <x-lucide-users class="size-5" />
                            </div>
                            <div>
                                <x-ui.card-title class="text-base font-bold">{{ $team->name }}</x-ui.card-title>
                                <span class="text-xs text-muted-foreground">{{ $team->tenant_users_count }} {{ Str::plural('member', $team->tenant_users_count) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-1">
                            <x-ui.button variant="ghost" size="xs" wire:click="openEditModal({{ $team->id }})">
                                <x-lucide-edit-2 class="size-3.5" />
                            </x-ui.button>
                            <x-ui.button 
                                variant="ghost" 
                                size="xs" 
                                wire:click="deleteTeam({{ $team->id }})" 
                                wire:confirm="Are you sure you want to delete this team? Agent assignments to this team will be removed."
                                class="text-destructive hover:text-destructive"
                            >
                                <x-lucide-trash-2 class="size-3.5" />
                            </x-ui.button>
                        </div>
                    </div>
                </x-ui.card-header>

                <x-ui.card-content class="space-y-4 pt-0">
                    <p class="text-xs text-muted-foreground line-clamp-2 min-h-[32px]">
                        {{ $team->description ?: 'No description provided.' }}
                    </p>

                    <!-- Assigned Team Members Avatars -->
                    <div class="pt-3 border-t border-border">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-muted-foreground block mb-2">Team Members</span>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($team->tenantUsers as $member)
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-xs font-medium bg-muted text-foreground border border-input">
                                    <span>{{ $member->name }}</span>
                                </span>
                            @empty
                                <span class="text-xs text-muted-foreground italic">No agents assigned yet.</span>
                            @endforelse
                        </div>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        @empty
            <div class="col-span-full p-8 text-center bg-muted/20 border border-border rounded-xl space-y-3">
                <div class="mx-auto size-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <x-lucide-users class="size-6" />
                </div>
                <h3 class="font-bold text-base text-foreground">No Teams Created</h3>
                <p class="text-xs text-muted-foreground max-w-sm mx-auto">Create teams to organize your support agents by specialty, tier, or shift.</p>
                <x-ui.button wire:click="openCreateModal" size="sm">
                    <x-lucide-plus class="size-4 mr-1.5" />
                    Create First Team
                </x-ui.button>
            </div>
        @endforelse
    </div>

    <!-- Create / Edit Team Modal -->
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
            <x-ui.card class="w-full max-w-md bg-card border border-border shadow-2xl">
                <x-ui.card-header class="pb-3 border-b border-border">
                    <div class="flex items-center justify-between">
                        <x-ui.card-title class="text-base">{{ $editingTeamId ? 'Edit Team' : 'Create New Team' }}</x-ui.card-title>
                        <button type="button" wire:click="$set('showFormModal', false)" class="text-muted-foreground hover:text-foreground">
                            <x-lucide-x class="size-4" />
                        </button>
                    </div>
                </x-ui.card-header>

                <form wire:submit="save">
                    <x-ui.card-content class="space-y-4 pt-4 text-xs">
                        <div>
                            <x-ui.label for="team_name">Team Name</x-ui.label>
                            <x-ui.input id="team_name" wire:model="name" placeholder="e.g. Tier 2 Technical Support" required />
                            @error('name') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <x-ui.label for="team_description">Description</x-ui.label>
                            <x-ui.textarea id="team_description" wire:model="description" rows="3" placeholder="Describe the team's responsibilities or scope..." />
                            @error('description') <span class="text-destructive text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Agent Checkbox Selection -->
                        <div>
                            <x-ui.label class="mb-2 block">Assign Agents to Team</x-ui.label>
                            <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-muted/40 rounded-lg border border-input">
                                @forelse($availableAgents as $agent)
                                    <label class="flex items-center space-x-2.5 cursor-pointer hover:bg-muted/60 p-1.5 rounded transition-colors">
                                        <input 
                                            type="checkbox" 
                                            value="{{ $agent->id }}" 
                                            wire:model="selectedAgentIds" 
                                            class="rounded border-input text-primary focus:ring-primary/40 size-4"
                                        />
                                        <div class="text-xs">
                                            <span class="font-semibold text-foreground block">{{ $agent->name }}</span>
                                            <span class="text-[11px] text-muted-foreground">{{ $agent->position ?: 'Support Agent' }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-xs text-muted-foreground italic">No active agents available.</p>
                                @endforelse
                            </div>
                        </div>
                    </x-ui.card-content>

                    <div class="p-4 border-t border-border flex justify-end space-x-2 bg-muted/20">
                        <x-ui.button type="button" variant="outline" wire:click="$set('showFormModal', false)">Cancel</x-ui.button>
                        <x-ui.button type="submit">{{ $editingTeamId ? 'Save Changes' : 'Create Team' }}</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    @endif
</div>
