<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-foreground">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-muted-foreground">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-ui.button variant="destructive" wire:click="$set('confirmingUserDeletion', true)">
        {{ __('Delete Account') }}
    </x-ui.button>

    <x-ui.dialog wire:model="confirmingUserDeletion">
        <x-ui.dialog-content>
            <x-ui.dialog-header>
                <x-ui.dialog-title>{{ __('Are you sure you want to delete your account?') }}</x-ui.dialog-title>
                <x-ui.dialog-description>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </x-ui.dialog-description>
            </x-ui.dialog-header>
            
            <form wire:submit="deleteUser" class="space-y-6">
                <div class="space-y-2">
                    <x-ui.label for="password" class="sr-only">{{ __('Password') }}</x-ui.label>
                    <x-ui.input
                        wire:model="password"
                        id="password"
                        name="password"
                        type="password"
                        placeholder="{{ __('Password') }}"
                    />
                    @error('password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>

                <x-ui.dialog-footer>
                    <x-ui.button type="button" variant="outline" wire:click="$set('confirmingUserDeletion', false)">
                        {{ __('Cancel') }}
                    </x-ui.button>

                    <x-ui.button type="submit" variant="destructive">
                        {{ __('Delete Account') }}
                    </x-ui.button>
                </x-ui.dialog-footer>
            </form>
        </x-ui.dialog-content>
    </x-ui.dialog>
</section>
