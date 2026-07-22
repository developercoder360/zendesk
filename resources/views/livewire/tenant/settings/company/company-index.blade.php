<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            Company Settings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Company Profile & Business Details</x-ui.card-title>
                    <x-ui.card-description>Manage organization info, default timezone, and contact details for your tenant workspace.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    @if($saved)
                        <div class="mb-4 p-3 bg-primary/10 border border-primary/20 text-primary text-sm rounded-md flex items-center justify-between">
                            <span>Company details updated successfully!</span>
                            <button wire:click="$set('saved', false)" class="text-xs underline">Dismiss</button>
                        </div>
                    @endif

                    <form wire:submit="save" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <x-ui.label for="company_name">Company / Workspace Name</x-ui.label>
                                <x-ui.input id="company_name" wire:model="company_name" required />
                                @error('company_name') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="subdomain">Subdomain</x-ui.label>
                                <div class="flex items-center">
                                    <x-ui.input id="subdomain" wire:model="subdomain" disabled class="bg-muted cursor-not-allowed" />
                                </div>
                                <span class="text-xs text-muted-foreground">Subdomain changes must be made via domain management.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <x-ui.label for="phone">Support Phone</x-ui.label>
                                <x-ui.input id="phone" wire:model="phone" placeholder="+1 (800) 555-0199" />
                                @error('phone') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="country">Country</x-ui.label>
                                <x-ui.input id="country" wire:model="country" placeholder="United States" />
                                @error('country') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="timezone">Timezone</x-ui.label>
                                <x-ui.select wire:model="timezone">
                                    <x-ui.select-trigger>
                                        <x-ui.select-value placeholder="Select Timezone" />
                                    </x-ui.select-trigger>
                                    <x-ui.select-content>
                                        <x-ui.select-item value="UTC">UTC (Coordinated Universal Time)</x-ui.select-item>
                                        <x-ui.select-item value="America/New_York">America/New_York (EST)</x-ui.select-item>
                                        <x-ui.select-item value="America/Chicago">America/Chicago (CST)</x-ui.select-item>
                                        <x-ui.select-item value="America/Denver">America/Denver (MST)</x-ui.select-item>
                                        <x-ui.select-item value="America/Los_Angeles">America/Los_Angeles (PST)</x-ui.select-item>
                                        <x-ui.select-item value="Europe/London">Europe/London (GMT)</x-ui.select-item>
                                        <x-ui.select-item value="Asia/Tokyo">Asia/Tokyo (JST)</x-ui.select-item>
                                    </x-ui.select-content>
                                </x-ui.select>
                                @error('timezone') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <x-ui.button type="submit">Save Changes</x-ui.button>
                        </div>
                    </form>
                </x-ui.card-content>
            </x-ui.card>

        </div>
    </div>
</div>
