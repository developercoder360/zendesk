<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = Auth::user();
        $domainRecord = \App\Models\Domain::where('tenant_id', $user->tenant_id)->first();
        
        if (! $domainRecord) {
            $this->redirect(route('dashboard', absolute: false), navigate: true);
            return;
        }

        $tenantDomain = $domainRecord->domain;
        $intended = session()->pull('url.intended', '/dashboard');
        
        $parsed = parse_url($intended);
        $path = $parsed['path'] ?? '/dashboard';
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $redirectUrl = $path . $query;

        $token = \Illuminate\Support\Str::random(64);
        cache()->put('tenant_login_' . $token, [
            'user_id' => $user->id,
            'redirect' => $redirectUrl,
        ], now()->addMinutes(5));

        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $tenantDomain . '/tenant-login?token=' . $token);
    }
}; ?>

<div>
    <x-ui.card class="w-full shadow-lg border-border/50">
        <x-ui.card-header class="space-y-1 text-center">
            <x-ui.card-title class="text-2xl font-bold tracking-tight">Welcome back</x-ui.card-title>
            <x-ui.card-description>Enter your email and password to sign in</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content>
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="login" class="space-y-4">
                <div class="space-y-2">
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input wire:model="form.email" id="email" type="email" required autofocus autocomplete="username" />
                    @error('form.email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <x-ui.label for="password">Password</x-ui.label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-medium text-primary hover:underline">Forgot password?</a>
                        @endif
                    </div>
                    <x-ui.input wire:model="form.password" id="password" type="password" required autocomplete="current-password" />
                    @error('form.password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                
                <div class="flex items-center space-x-2 pt-2">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-input bg-background text-primary focus:ring-primary focus:ring-offset-background">
                    <x-ui.label for="remember" class="font-normal text-sm cursor-pointer">Remember me</x-ui.label>
                </div>
                
                <div class="pt-2">
                    <x-ui.button type="submit" class="w-full">
                        <span wire:loading.remove wire:target="login">Sign In</span>
                        <span wire:loading wire:target="login">Signing in...</span>
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card-content>
        <x-ui.card-footer class="flex items-center justify-center pb-6">
            <div class="text-sm text-muted-foreground text-center">
                Don't have an account? <a href="{{ route('register') }}" wire:navigate class="text-primary hover:underline font-medium">Start your free trial</a>
            </div>
        </x-ui.card-footer>
    </x-ui.card>
</div>
