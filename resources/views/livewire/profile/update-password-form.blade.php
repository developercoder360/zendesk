<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <div>
            <x-ui.label for="update_password_current_password">{{ __('Current Password') }}</x-ui.label>
            <x-ui.input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            @error('current_password') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <div>
            <x-ui.label for="update_password_password">{{ __('New Password') }}</x-ui.label>
            <x-ui.input wire:model="password" id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            @error('password') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <div>
            <x-ui.label for="update_password_password_confirmation">{{ __('Confirm Password') }}</x-ui.label>
            <x-ui.input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            @error('password_confirmation') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4">
            <x-ui.button>{{ __('Save') }}</x-ui.button>

            <div x-data="{ shown: false, timeout: null }" x-on:password-updated.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);" x-show="shown" x-transition:leave.opacity.duration.1500ms style="display: none;" class="text-sm text-muted-foreground me-3">
                {{ __('Saved.') }}
            {{ __('Saved.') }}</div>
        </div>
    </form>
</section>
