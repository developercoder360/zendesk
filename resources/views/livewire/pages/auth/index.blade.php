<?php

use App\Livewire\Forms\LoginForm;
use App\Models\User;
use App\Models\Plan;
use App\DTOs\RegistrationDTO;
use App\DTOs\PaymentIntent;
use App\Contracts\PaymentGatewayContract;
use App\Actions\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    // Shared state
    public string $tab = 'signin';
    
    // Login Form
    public LoginForm $loginForm;

    // Registration Form state
    public string $step = 'plan'; // plan, form, payment
    public $plans = [];

    // Registration Form data
    public int $planId = 0;
    public string $ownerName = '';
    public string $companyName = '';
    public string $companySlug = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone = '';
    public string $country = '';
    public string $timezone = '';
    
    // Payment
    public string $paymentMethodId = 'tok_mock_success';
    public ?string $paymentError = null;

    public function mount()
    {
        // Set default tab based on the current route
        if (Route::currentRouteName() === 'register') {
            $this->tab = 'signup';
        }

        // Initialize Plans for Registration
        $this->plans = Plan::all();
        if ($this->plans->isEmpty()) {
            $plan = Plan::create(['name' => 'Starter', 'slug' => 'starter', 'price' => 2900]);
            $this->plans = collect([$plan]);
        }
    }

    /**
     * Handle an incoming authentication request (Login).
     */
    public function login(): void
    {
        $this->validate([
            'loginForm.email' => 'required|email',
            'loginForm.password' => 'required',
        ]);

        $this->loginForm->authenticate();

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

        $token = Str::random(64);
        cache()->put('tenant_login_' . $token, [
            'user_id' => $user->id,
            'redirect' => $redirectUrl,
        ], now()->addMinutes(5));

        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $tenantDomain . '/tenant-login?token=' . $token);
    }

    // --- REGISTRATION METHODS ---

    public function selectPlan($planId)
    {
        $this->planId = $planId;
        $this->step = 'form';
    }

    public function updatedCompanyName()
    {
        if (empty($this->companySlug)) {
            $this->companySlug = Str::slug($this->companyName);
        }
    }

    public function submitRegistrationForm()
    {
        $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';
        $fullDomain = $this->companySlug . '.' . $centralDomain;

        $this->validate([
            'ownerName' => ['required', 'string', 'max:255'],
            'companyName' => ['required', 'string', 'max:255'],
            'companySlug' => ['required', 'string', 'max:255', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:2'],
            'timezone' => ['nullable', 'string', 'max:255'],
        ]);

        if (\App\Models\Domain::where('domain', $fullDomain)->exists()) {
            $this->addError('companySlug', 'This slug is already taken or unavailable. Please try a different one.');
            return;
        }

        $this->step = 'payment';
    }

    public function processPayment(PaymentGatewayContract $gateway, RegisterTenant $registerTenant)
    {
        // Rate Limiting to prevent abuse (Enumeration/Spam)
        if (RateLimiter::tooManyAttempts('register:'.request()->ip(), 5)) {
            $this->addError('paymentError', 'Too many registration attempts. Please try again later.');
            return;
        }
        RateLimiter::hit('register:'.request()->ip());

        $this->paymentError = null;

        $intent = new PaymentIntent(
            amount: Plan::find($this->planId)->price,
            currency: 'usd',
            paymentMethodId: $this->paymentMethodId,
            email: $this->email
        );

        $result = $gateway->charge($intent);

        if (! $result->successful()) {
            $this->addError('paymentError', $result->errorMessage);
            return;
        }

        $dto = new RegistrationDTO(
            ownerName: $this->ownerName,
            companyName: $this->companyName,
            companySlug: $this->companySlug,
            email: $this->email,
            password: $this->password,
            phone: $this->phone,
            country: $this->country,
            timezone: $this->timezone,
            planId: $this->planId,
        );

        $tenant = $registerTenant->execute($dto);
        
        // Log in the user centrally
        $owner = User::where('email', $this->email)->first();
        Auth::login($owner);
        
        $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';
        $domain = $this->companySlug . '.' . $centralDomain;
        
        $token = Str::random(64);
        cache()->put('tenant_login_' . $token, [
            'user_id' => $owner->id,
            'redirect' => '/dashboard',
        ], now()->addMinutes(5));

        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $domain . '/tenant-login?token=' . $token);
    }
}; ?>

<div class="w-full">
    <x-ui.tabs wire:model="tab" class="w-full">
        <x-ui.tabs-list class="grid w-full grid-cols-2">
            <x-ui.tabs-trigger value="signin">Sign In</x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="signup">Create Account</x-ui.tabs-trigger>
        </x-ui.tabs-list>

        <!-- SIGN IN TAB -->
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
                    <x-ui.label for="login-email">Email</x-ui.label>
                    <x-ui.input wire:model="loginForm.email" id="login-email" type="email" placeholder="you@example.com" required autofocus autocomplete="username">
                        <x-slot:leading><x-lucide-mail /></x-slot:leading>
                    </x-ui.input>
                    @error('loginForm.email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                
                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <x-ui.label for="login-password">Password</x-ui.label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate class="text-primary text-sm hover:underline">Forgot password?</a>
                        @endif
                    </div>
                    <x-ui.input wire:model="loginForm.password" id="login-password" type="password" placeholder="••••••••" required autocomplete="current-password">
                        <x-slot:leading><x-lucide-lock /></x-slot:leading>
                    </x-ui.input>
                    @error('loginForm.password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                
                <label class="flex items-center gap-2 text-sm">
                    <input wire:model="loginForm.remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-input bg-background text-primary focus:ring-primary focus:ring-offset-background">
                    Remember me for 30 days
                </label>
                
                <x-ui.button type="submit" class="w-full">
                    <span wire:loading.remove wire:target="login">Sign in</span>
                    <span wire:loading wire:target="login">Signing in...</span>
                </x-ui.button>
            </form>
        </x-ui.tabs-content>

        <!-- CREATE ACCOUNT TAB -->
        <x-ui.tabs-content value="signup" class="mt-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold tracking-tight">Create your account</h1>
                <p class="text-muted-foreground mt-1 text-sm">Start your 14-day free trial. No credit card required during trial.</p>
            </div>
            
            <div class="flex items-center space-x-2 mb-6 text-xs font-medium text-muted-foreground">
                <span class="{{ $step === 'plan' ? 'text-primary' : '' }}">1. Plan</span>
                <span>&mdash;</span>
                <span class="{{ $step === 'form' ? 'text-primary' : '' }}">2. Details</span>
                <span>&mdash;</span>
                <span class="{{ $step === 'payment' ? 'text-primary' : '' }}">3. Payment</span>
            </div>

            @if ($step === 'plan')
                <div class="space-y-4">
                    @foreach ($plans as $plan)
                        <div wire:click="selectPlan({{ $plan->id }})" class="border rounded-lg p-4 cursor-pointer hover:border-primary hover:bg-primary/5 transition-all flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-foreground">{{ $plan->name }}</h3>
                                <p class="text-sm text-muted-foreground">${{ number_format($plan->price / 100, 2) }} / month</p>
                            </div>
                            <x-lucide-chevron-right class="w-5 h-5 text-muted-foreground" />
                        </div>
                    @endforeach
                </div>
            @elseif ($step === 'form')
                <form wire:submit="submitRegistrationForm" class="space-y-4">
                    <div class="space-y-2">
                        <x-ui.label for="companyName">Company Name</x-ui.label>
                        <x-ui.input wire:model.live="companyName" id="companyName" type="text" required autofocus />
                        @error('companyName') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="companySlug">Workspace URL</x-ui.label>
                        <div class="flex items-center space-x-2">
                            <x-ui.input wire:model="companySlug" id="companySlug" type="text" required class="flex-1" />
                            <span class="text-sm text-muted-foreground whitespace-nowrap">.{{ config('tenancy.central_domains')[0] ?? 'zendesk.test' }}</span>
                        </div>
                        @error('companySlug') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="ownerName">Your Name</x-ui.label>
                        <x-ui.input wire:model="ownerName" id="ownerName" type="text" required />
                        @error('ownerName') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="reg-email">Work Email</x-ui.label>
                        <x-ui.input wire:model="email" id="reg-email" type="email" required />
                        @error('email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="reg-password">Password</x-ui.label>
                        <x-ui.input wire:model="password" id="reg-password" type="password" required />
                        @error('password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="password_confirmation">Confirm Password</x-ui.label>
                        <x-ui.input wire:model="password_confirmation" id="password_confirmation" type="password" required />
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <button type="button" wire:click="$set('step', 'plan')" class="text-sm text-muted-foreground hover:text-foreground">Back</button>
                        <x-ui.button type="submit">Continue to Payment</x-ui.button>
                    </div>
                </form>
            @elseif ($step === 'payment')
                <div class="space-y-6">
                    @if ($paymentError)
                        <div class="bg-destructive/10 text-destructive p-3 rounded-md text-sm">
                            {{ $paymentError }}
                        </div>
                    @endif
                    
                    <div class="bg-muted p-4 rounded-lg flex justify-between items-center">
                        <span class="font-medium">{{ App\Models\Plan::find($planId)->name ?? '' }} Plan</span>
                        <span class="font-bold">${{ number_format((App\Models\Plan::find($planId)->price ?? 0) / 100, 2) }} <span class="text-sm font-normal text-muted-foreground">/mo</span></span>
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="paymentMethodId">Select Payment Method (Mock)</x-ui.label>
                        <select wire:model="paymentMethodId" id="paymentMethodId" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="tok_mock_success">Valid Card (Success)</option>
                            <option value="tok_fail">Declined Card (Fail)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <button type="button" wire:click="$set('step', 'form')" class="text-sm text-muted-foreground hover:text-foreground">Back</button>
                        <x-ui.button wire:click="processPayment" class="w-full sm:w-auto">
                            <span wire:loading.remove wire:target="processPayment">Complete Registration</span>
                            <span wire:loading wire:target="processPayment">Processing...</span>
                        </x-ui.button>
                    </div>
                </div>
            @endif
        </x-ui.tabs-content>
    </x-ui.tabs>
</div>
