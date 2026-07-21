<div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div>
            <x-ui.label for="password">{{ __('Password') }}</x-ui.label>

            <x-ui.input wire:model="password"
                          id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            @error('password') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end mt-4">
            <x-ui.button>
                {{ __('Confirm') }}
            </x-ui.button>
        </div>
    </form>
</div>
