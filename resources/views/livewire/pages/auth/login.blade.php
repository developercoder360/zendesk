<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.central-auth')] class extends Component
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
            $this->redirect(route('central.dashboard', absolute: false), navigate: true);
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
    <x-ui.tabs value="signin" class="w-full">
        <x-ui.tabs-list class="grid w-full grid-cols-2">
            <x-ui.tabs-trigger value="signin">Sign in</x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="signup" wire:click="redirect('{{ route('register') }}', true)">Create account</x-ui.tabs-trigger>
        </x-ui.tabs-list>

        <x-ui.tabs-content value="signin" class="mt-6">
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
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input wire:model="form.email" id="email" type="email" placeholder="you@example.com" required autofocus autocomplete="username">
                        <x-slot:leading><x-lucide-mail /></x-slot:leading>
                    </x-ui.input>
                    @error('form.email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                
                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <x-ui.label for="password">Password</x-ui.label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate class="text-primary text-sm hover:underline">Forgot password?</a>
                        @endif
                    </div>
                    <x-ui.input wire:model="form.password" id="password" type="password" placeholder="••••••••" required autocomplete="current-password">
                        <x-slot:leading><x-lucide-lock /></x-slot:leading>
                    </x-ui.input>
                    @error('form.password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                
                <label class="flex items-center gap-2 text-sm">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-input bg-background text-primary focus:ring-primary focus:ring-offset-background">
                    Remember me for 30 days
                </label>
                
                <x-ui.button type="submit" class="w-full">
                    <span wire:loading.remove wire:target="login">Sign in</span>
                    <span wire:loading wire:target="login">Signing in...</span>
                </x-ui.button>
            </form>
        </x-ui.tabs-content>
    </x-ui.tabs>
</div>
