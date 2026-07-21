<div class="w-full">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight">Create your account</h1>
        <p class="text-muted-foreground mt-1 text-sm">Start your 14-day free trial. No credit card required during trial.</p>
    </div>
    <div class="flex items-center space-x-2 mb-6 text-xs font-medium text-muted-foreground">
        <span class="{{ $step === 'package' ? 'text-primary' : '' }}">1. Package</span>
        <span>&mdash;</span>
        <span class="{{ $step === 'form' ? 'text-primary' : '' }}">2. Details</span>
        <span>&mdash;</span>
        <span class="{{ $step === 'payment' ? 'text-primary' : '' }}">3. Payment</span>
    </div>

    @if ($step === 'package')
        <div class="space-y-4">
            @foreach ($packages as $package)
                <div wire:click="selectPackage({{ $package->id }})"
                    class="border rounded-lg p-4 cursor-pointer hover:border-primary hover:bg-primary/5 transition-all flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-foreground">{{ $package->name }} ({{ ucfirst($package->billing_interval) }})</h3>
                        <p class="text-sm text-muted-foreground">${{ number_format($package->price / 100, 2) }}
                            / {{ $package->billing_interval === 'monthly' ? 'mo' : 'yr' }}</p>
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
                @error('companyName')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <x-ui.label for="companySlug">Workspace URL</x-ui.label>
                <div class="flex items-center space-x-2">
                    <x-ui.input wire:model="companySlug" id="companySlug" type="text" required
                        class="flex-1" />
                    <span
                        class="text-sm text-muted-foreground whitespace-nowrap">.{{ config('tenancy.central_domains')[0] ?? 'zendesk.test' }}</span>
                </div>
                @error('companySlug')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <x-ui.label for="ownerName">Your Name</x-ui.label>
                <x-ui.input wire:model="ownerName" id="ownerName" type="text" required />
                @error('ownerName')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <x-ui.label for="reg-email">Work Email</x-ui.label>
                <x-ui.input wire:model="email" id="reg-email" type="email" required />
                @error('email')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <x-ui.label for="reg-password">Password</x-ui.label>
                <x-ui.input wire:model="password" id="reg-password" type="password" required />
                @error('password')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <x-ui.label for="password_confirmation">Confirm Password</x-ui.label>
                <x-ui.input wire:model="password_confirmation" id="password_confirmation" type="password"
                    required />
            </div>
            <div class="flex items-center justify-between pt-4">
                <button type="button" wire:click="$set('step', 'package')"
                    class="text-sm text-muted-foreground hover:text-foreground">Back</button>
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
                <span class="font-medium">{{ App\Models\Package::find($packageId)->name ?? '' }} Package</span>
                <span
                    class="font-bold">${{ number_format((App\Models\Package::find($packageId)->price ?? 0) / 100, 2) }}
                    </span>
            </div>
            <div class="space-y-2">
                <x-ui.label for="paymentMethodId">Select Payment Method (Mock)</x-ui.label>
                <select wire:model="paymentMethodId" id="paymentMethodId"
                    class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    <option value="tok_mock_success">Valid Card (Success)</option>
                    <option value="tok_fail">Declined Card (Fail)</option>
                </select>
            </div>
            <div class="flex items-center justify-between pt-4">
                <button type="button" wire:click="$set('step', 'form')"
                    class="text-sm text-muted-foreground hover:text-foreground">Back</button>
                <x-ui.button wire:click="processPayment" class="w-full sm:w-auto">
                    <span wire:loading.remove wire:target="processPayment">Complete Registration</span>
                    <span wire:loading wire:target="processPayment">Processing...</span>
                </x-ui.button>
            </div>
        </div>
    @endif
    <div class="mt-6 text-center text-sm text-muted-foreground">
        Already have an account? <a href="{{ route('login') }}" wire:navigate class="underline hover:text-primary">Sign in</a>
    </div>
</div>
