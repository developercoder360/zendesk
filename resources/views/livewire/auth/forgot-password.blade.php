<div>
    <x-ui.card class="w-full sm:max-w-md shadow-lg border-border/50">
        <x-ui.card-header class="space-y-1 text-center">
            <x-ui.card-title class="text-2xl font-bold tracking-tight">Forgot password</x-ui.card-title>
            <x-ui.card-description>No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content>
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="sendPasswordResetLink" class="space-y-4">
                <div class="space-y-2">
                    <x-ui.label for="email">Email Address</x-ui.label>
                    <x-ui.input wire:model="email" id="email" type="email" required autofocus />
                    @error('email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <x-ui.button type="submit" class="w-full">
                        <span wire:loading.remove wire:target="sendPasswordResetLink">Email Password Reset Link</span>
                        <span wire:loading wire:target="sendPasswordResetLink">Sending...</span>
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card-content>
        <x-ui.card-footer class="flex items-center justify-center pb-6">
            <div class="text-sm text-muted-foreground text-center">
                Remember your password? <a href="{{ route('login') }}" wire:navigate class="text-primary hover:underline font-medium">Log in</a>
            </div>
        </x-ui.card-footer>
    </x-ui.card>
</div>
