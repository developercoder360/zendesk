<x-ui.card variant="sectioned" id="profile" class="scroll-mt-24">
    <x-ui.card-header>
        <x-ui.card-title>Profile</x-ui.card-title>
        <x-ui.card-description>This is how others will see you.</x-ui.card-description>
    </x-ui.card-header>
    
    <form wire:submit="updateProfileInformation">
        <x-ui.card-content class="space-y-5">
            <div class="flex items-center gap-4">
                <x-ui.avatar class="size-16">
                    <x-ui.avatar-fallback class="text-lg">{{ substr(auth()->user()->name, 0, 2) }}</x-ui.avatar-fallback>
                </x-ui.avatar>
                <div class="flex gap-2">
                    <x-ui.button type="button" variant="outline" size="sm"><x-lucide-upload class="size-4 mr-2" /> Change</x-ui.button>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <x-ui.label for="name">Full name</x-ui.label>
                    <x-ui.input wire:model="name" id="name" required />
                    @error('name') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                </div>
                <div class="grid gap-2">
                    <x-ui.label for="email">Email address</x-ui.label>
                    <x-ui.input wire:model="email" id="email" type="email" required />
                    @error('email') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-ui.card-content>
        <x-ui.card-footer class="justify-end gap-2">
            <x-ui.button type="submit">
                <span wire:loading.remove wire:target="updateProfileInformation">Save changes</span>
                <span wire:loading wire:target="updateProfileInformation">Saving...</span>
            </x-ui.button>
            
            <x-ui.badge variant="secondary" class="ml-2" x-data="{ shown: false, timeout: null }"
                x-on:profile-updated.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);"
                x-show="shown" style="display: none;">
                Saved.
            </x-ui.badge>
        </x-ui.card-footer>
    </form>
</x-ui.card>
