<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->where(fn ($query) => $query->where('tenant_id', tenant('id')))->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-ui.label for="name">{{ __('Name') }}</x-ui.label>
            <x-ui.input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            @error('name') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror
        </div>

        <div>
            <x-ui.label for="email">{{ __('Email') }}</x-ui.label>
            <x-ui.input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            @error('email') <p class="mt-2 text-sm text-destructive">{{ $message }}</p> @enderror

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-ui.button>{{ __('Save') }}</x-ui.button>

            <div x-data="{ shown: false, timeout: null }" x-on:profile-updated.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);" x-show="shown" x-transition:leave.opacity.duration.1500ms style="display: none;" class="text-sm text-muted-foreground me-3">
                {{ __('Saved.') }}
            {{ __('Saved.') }}</div>
        </div>
    </form>
</section>
