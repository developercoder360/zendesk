<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Package;
use App\DTOs\RegistrationDTO;
use App\DTOs\PaymentIntent;
use App\Contracts\PaymentGatewayContract;
use App\Actions\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Support\Str;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    #[Url]
    public ?string $plan = null;

    #[Url]
    public ?string $returnTo = null;

    // Registration Form state
    public string $step = 'package'; // package, form, payment
    public $packages = [];
    
    // Registration Form data
    public int $packageId = 0;
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
        $this->packages = Package::where('is_active', true)->get();
        if ($this->packages->isEmpty()) {
            $package = Package::create([
                'name' => 'Starter', 
                'price' => 2900, 
                'billing_interval' => 'monthly', 
                'agent_limit' => 3, 
                'chat_limit_monthly' => 1000,
                'slug' => 'starter-monthly'
            ]);
            $this->packages = collect([$package]);
        }

        if ($this->plan) {
            $selectedPackage = Package::where('slug', $this->plan)->first();
            if ($selectedPackage) {
                $this->packageId = $selectedPackage->id;
                $this->step = 'form';
            }
        }
    }

    private function safeRedirectPath(?string $path): string
    {
        $default = '/dashboard';
        if (! $path) return $default;
        // Must start with a single "/" and NOT "//" or "/\"
        if (! preg_match('#^/(?!/)(?!\\\\)#', $path)) return $default;
        // Reject anything containing a scheme (e.g. javascript:, http:) even if url-encoded
        if (preg_match('#^[a-zA-Z][a-zA-Z0-9+.-]*:#', $path)) return $default;
        return $path;
    }

    public function selectPackage($packageId)
    {
        $this->packageId = $packageId;
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
        // Rate Limiting to prevent abuse
        if (RateLimiter::tooManyAttempts('register:' . request()->ip(), 5)) {
            $this->addError('paymentError', 'Too many registration attempts. Please try again later.');
            return;
        }
        RateLimiter::hit('register:' . request()->ip());
        $this->paymentError = null;
        
        $package = Package::find($this->packageId);
        $intent = new PaymentIntent(
            amount: $package->price, 
            currency: 'usd', 
            paymentMethodId: $this->paymentMethodId, 
            email: $this->email
        );
        $result = $gateway->charge($intent);
        
        if (!$result->successful()) {
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
            packageId: $this->packageId
        );
        
        $tenant = $registerTenant->execute($dto);
        
        // Log in the user centrally
        $owner = User::where('email', $this->email)->first();
        Auth::login($owner);
        
        $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';
        $domain = $this->companySlug . '.' . $centralDomain;
        $token = Str::random(64);
        
        $redirectUrl = $this->safeRedirectPath($this->returnTo);
        
        cache()->put(
            'tenant_login_' . $token,
            [
                'user_id' => $owner->id,
                'redirect' => $redirectUrl,
            ],
            now()->addMinutes(5),
        );
        
        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $domain . '/tenant-login?token=' . $token);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
