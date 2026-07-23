<div>
    <nav class="flex items-center text-sm text-muted-foreground mb-4">
        <span class="hover:text-foreground transition-colors cursor-pointer">Settings</span>
        <span class="inline-flex items-center mx-1.5"><x-lucide-chevron-right class="size-3.5" /></span>
        <span class="font-medium text-foreground">Account</span>
    </nav>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Account Settings</h1>
            <p class="text-sm text-muted-foreground">Manage your personal agent account details.</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Information -->
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Profile Information</x-ui.card-title>
                    <x-ui.card-description>Update your account's profile information and contact details.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    @if($savedProfile)
                        <div class="mb-4 p-3 bg-primary/10 border border-primary/20 text-primary text-sm rounded-md flex items-center justify-between">
                            <span>Profile updated successfully!</span>
                            <button wire:click="$set('savedProfile', false)" class="text-xs underline">Dismiss</button>
                        </div>
                    @endif

                    <form wire:submit="updateProfile" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <x-ui.label for="name">Full Name</x-ui.label>
                                <x-ui.input id="name" wire:model="name" required />
                                @error('name') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="email">Email Address</x-ui.label>
                                <x-ui.input id="email" type="email" wire:model="email" required />
                                @error('email') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <x-ui.label for="phone">Phone Number</x-ui.label>
                                <x-ui.input id="phone" wire:model="phone" placeholder="+1 (555) 000-0000" />
                                @error('phone') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="position">Job Title / Position</x-ui.label>
                                <x-ui.input id="position" wire:model="position" placeholder="e.g. Senior Support Agent" />
                                @error('position') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <x-ui.button type="submit">Save Profile</x-ui.button>
                        </div>
                    </form>
                </x-ui.card-content>
            </x-ui.card>

            <!-- Password Change -->
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Update Password</x-ui.card-title>
                    <x-ui.card-description>Ensure your account is using a long, random password to stay secure.</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    @if($savedPassword)
                        <div class="mb-4 p-3 bg-primary/10 border border-primary/20 text-primary text-sm rounded-md flex items-center justify-between">
                            <span>Password updated successfully!</span>
                            <button wire:click="$set('savedPassword', false)" class="text-xs underline">Dismiss</button>
                        </div>
                    @endif

                    <form wire:submit="updatePassword" class="space-y-4">
                        <div class="space-y-2 max-w-md">
                            <x-ui.label for="current_password">Current Password</x-ui.label>
                            <x-ui.input id="current_password" type="password" wire:model="current_password" required />
                            @error('current_password') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 max-w-md">
                            <div class="space-y-2">
                                <x-ui.label for="password">New Password</x-ui.label>
                                <x-ui.input id="password" type="password" wire:model="password" required />
                                @error('password') <span class="text-xs text-destructive">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <x-ui.label for="password_confirmation">Confirm Password</x-ui.label>
                                <x-ui.input id="password_confirmation" type="password" wire:model="password_confirmation" required />
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <x-ui.button type="submit">Update Password</x-ui.button>
                        </div>
                    </form>
                </x-ui.card-content>
            </x-ui.card>

        </div>
    </div>
</div>
