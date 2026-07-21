<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    // Login Form
    public LoginForm $loginForm;

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

    public function render()
    {
        return view('livewire.auth.login');
    }
}
