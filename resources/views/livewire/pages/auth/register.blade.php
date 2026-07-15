<?php

use App\Models\User;
use App\Models\Plan;
use App\DTOs\RegistrationDTO;
use App\DTOs\PaymentIntent;
use App\Contracts\PaymentGatewayContract;
use App\Actions\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\RateLimiter;

new #[Layout('layouts.auth')] class extends Component {
    public string $step = 'plan'; // plan, form, payment
    public $plans = [];

    // Form data
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
        $this->plans = Plan::all();
        if ($this->plans->isEmpty()) {
            $plan = Plan::create(['name' => 'Starter', 'slug' => 'starter', 'price' => 2900]);
            $this->plans = collect([$plan]);
        }
    }

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

    public function submitForm()
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
        
        $token = \Illuminate\Support\Str::random(64);
        cache()->put('tenant_login_' . $token, [
            'user_id' => $owner->id,
            'redirect' => '/dashboard',
        ], now()->addMinutes(5));

        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $domain . '/tenant-login?token=' . $token);
    }
}; ?>

<div>
    <x-ui.card class="w-full sm:max-w-md shadow-lg border-border/50">
        <x-ui.card-header class="space-y-1 text-center">
            <x-ui.card-title class="text-2xl font-bold tracking-tight">Create your account</x-ui.card-title>
            <x-ui.card-description>Start your 14-day free trial. No credit card required during trial.</x-ui.card-description>
            
            <div class="flex items-center justify-center space-x-2 mt-4 text-xs font-medium text-muted-foreground">
                <span class="{{ $step === 'plan' ? 'text-primary' : '' }}">1. Plan</span>
                <span>&mdash;</span>
                <span class="{{ $step === 'form' ? 'text-primary' : '' }}">2. Details</span>
                <span>&mdash;</span>
                <span class="{{ $step === 'payment' ? 'text-primary' : '' }}">3. Payment</span>
            </div>
        </x-ui.card-header>

        <x-ui.card-content>
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
                <form wire:submit="submitForm" class="space-y-4">
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
                        <x-ui.label for="email">Work Email</x-ui.label>
                        <x-ui.input wire:model="email" id="email" type="email" required />
                        @error('email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="password">Password</x-ui.label>
                        <x-ui.input wire:model="password" id="password" type="password" required />
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
                        <span class="font-medium">{{ App\Models\Plan::find($planId)->name }} Plan</span>
                        <span class="font-bold">${{ number_format(App\Models\Plan::find($planId)->price / 100, 2) }} <span class="text-sm font-normal text-muted-foreground">/mo</span></span>
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
        </x-ui.card-content>
        <x-ui.card-footer class="flex items-center justify-center pb-6">
            <div class="text-sm text-muted-foreground text-center">
                Already have an account? <a href="{{ route('login') }}" wire:navigate class="text-primary hover:underline font-medium">Log in</a>
            </div>
        </x-ui.card-footer>
    </x-ui.card>
</div>
