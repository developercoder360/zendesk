<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use App\Models\User;
use App\Models\Package;
use App\Models\Domain;
use App\DTOs\RegistrationDTO;
use App\DTOs\PaymentIntent;
use App\Contracts\PaymentGatewayContract;
use App\Actions\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    // Shared state
    public string $tab = 'signin';
    // Login Form
    public LoginForm $loginForm;
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
        // Set default tab based on the current route
        if (Route::currentRouteName() === 'register') {
            $this->tab = 'signup';
        }
        // Initialize Packages for Registration
        $this->packages = Package::all();
        if ($this->packages->isEmpty()) {
            $package = Package::create(['name' => 'Starter', 'price' => 2900, 'billing_interval' => 'monthly', 'agent_limit' => 3, 'chat_limit_monthly' => 1000]);
            $this->packages = collect([$package]);
        }
    }

    public function login(): void
    {
        $this->validate([
            'loginForm.email' => 'required|email',
            'loginForm.password' => 'required',
        ]);
        $user = $this->loginForm->authenticate();
        if ($user->tenant_id === null) {
            // Super Admin Login
            Auth::login($user, $this->loginForm->remember);
            Session::regenerate();
            $this->redirect(route('central.dashboard', absolute: false), navigate: true);
            return;
        }
        // Tenant Login - DO NOT create a session on the Central domain
        $domainRecord = Domain::where('tenant_id', $user->tenant_id)->first();
        if (!$domainRecord) {
            throw ValidationException::withMessages([
                'loginForm.email' => 'Your workspace is not fully set up.',
            ]);
        }
        $tenantDomain = $domainRecord->domain;
        $intended = session()->pull('url.intended', '/dashboard');
        $parsed = parse_url($intended);
        $path = $parsed['path'] ?? '/dashboard';
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $redirectUrl = $path . $query;
        $token = Str::random(64);
        cache()->put(
            'tenant_login_' . $token,
            [
                'user_id' => $user->id,
                'redirect' => $redirectUrl,
                'remember' => $this->loginForm->remember,
            ],
            now()->addMinutes(5),
        );
        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $tenantDomain . '/tenant-login?token=' . $token);
    }

    // --- REGISTRATION METHODS ---
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
        // Rate Limiting to prevent abuse (Enumeration/Spam)
        if (RateLimiter::tooManyAttempts('register:' . request()->ip(), 5)) {
            $this->addError('paymentError', 'Too many registration attempts. Please try again later.');
            return;
        }
        RateLimiter::hit('register:' . request()->ip());
        $this->paymentError = null;
        $intent = new PaymentIntent(amount: Package::find($this->packageId)->price, currency: 'usd', paymentMethodId: $this->paymentMethodId, email: $this->email);
        $result = $gateway->charge($intent);
        if (!$result->successful()) {
            $this->addError('paymentError', $result->errorMessage);
            return;
        }
        $dto = new RegistrationDTO(ownerName: $this->ownerName, companyName: $this->companyName, companySlug: $this->companySlug, email: $this->email, password: $this->password, phone: $this->phone, country: $this->country, timezone: $this->timezone, packageId: $this->packageId);
        $tenant = $registerTenant->execute($dto);
        // Log in the user centrally
        $owner = User::where('email', $this->email)->first();
        Auth::login($owner);
        $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';
        $domain = $this->companySlug . '.' . $centralDomain;
        $token = Str::random(64);
        cache()->put(
            'tenant_login_' . $token,
            [
                'user_id' => $owner->id,
                'redirect' => '/dashboard',
            ],
            now()->addMinutes(5),
        );
        $scheme = request()->getScheme();
        $this->redirect($scheme . '://' . $domain . '/tenant-login?token=' . $token);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
