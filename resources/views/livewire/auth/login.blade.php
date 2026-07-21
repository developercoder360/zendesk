<div class="w-full">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight">Welcome back</h1>
        <p class="text-muted-foreground mt-1 text-sm">Sign in to your workspace to continue.</p>
    </div>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif
    <form wire:submit="login" class="grid gap-4">
        <div class="grid gap-2">
            <x-ui.label for="login-email">Email</x-ui.label>
            <x-ui.input wire:model="loginForm.email" id="login-email" type="email"
                placeholder="you@example.com" required autofocus autocomplete="username">
                <x-slot:leading><x-lucide-mail /></x-slot:leading>
            </x-ui.input>
            @error('loginForm.email')
                <p class="text-sm text-destructive">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <div class="flex items-center justify-between">
                <x-ui.label for="login-password">Password</x-ui.label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                        class="text-primary text-sm hover:underline">Forgot password?</a>
                @endif
            </div>
            <x-ui.input wire:model="loginForm.password" id="login-password" type="password"
                placeholder="••••••••" required autocomplete="current-password">
                <x-slot:leading><x-lucide-lock /></x-slot:leading>
            </x-ui.input>
            @error('loginForm.password')
                <p class="text-sm text-destructive">{{ $message }}</p>
            @enderror
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input wire:model="loginForm.remember" id="remember" type="checkbox"
                class="h-4 w-4 rounded border-input bg-background text-primary focus:ring-primary focus:ring-offset-background">
            Remember me for 30 days
        </label>
        <x-ui.button type="submit" class="w-full">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login">Signing in...</span>
        </x-ui.button>
    </form>
    
    <div class="mt-6 text-center text-sm text-muted-foreground">
        Don't have an account? <a href="{{ route('register') }}" wire:navigate class="underline hover:text-primary">Sign up</a>
    </div>
</div>
