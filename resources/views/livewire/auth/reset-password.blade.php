<div>
    <form wire:submit="resetPassword">
        <!-- Email Address -->
        <div>
            <x-ui.label for="email">{{ __('Email') }}</x-ui.label>
            <x-ui.input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            @error('email') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-ui.label for="password">{{ __('Password') }}</x-ui.label>
            <x-ui.input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            @error('password') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-ui.label for="password_confirmation">{{ __('Confirm Password') }}</x-ui.label>

            <x-ui.input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />

            @error('password_confirmation') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-ui.button>
                {{ __('Reset Password') }}
            </x-ui.button>
        </div>
    </form>
</div>
