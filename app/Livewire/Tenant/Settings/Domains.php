<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Domain;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class Domains extends Component
{
    public string $newSubdomain = '';

    public function addDomain()
    {
        $this->validate([
            'newSubdomain' => ['required', 'string', 'max:50', 'alpha_dash'],
        ]);

        if (! tenant()->canAddSubdomain()) {
            $this->addError('newSubdomain', 'You have reached your subdomain limit.');
            return;
        }

        $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';
        $fullDomain = $this->newSubdomain . '.' . $centralDomain;

        if (Domain::where('domain', $fullDomain)->exists()) {
            $this->addError('newSubdomain', 'This subdomain is already taken or unavailable. Please try a different one.');
            return;
        }

        Domain::create([
            'domain' => $fullDomain,
            'tenant_id' => tenant('id'),
            'is_primary' => false,
        ]);

        $this->newSubdomain = '';
        session()->flash('status', 'Subdomain added successfully.');
    }

    public function render()
    {
        return view('livewire.tenant.settings.domains', [
            'domains' => tenant()->domains()->orderBy('created_at')->get(),
            'canAdd' => tenant()->canAddSubdomain(),
            'slotsRemaining' => tenant()->remainingSubdomainSlots(),
            'limit' => tenant()->package?->max_subdomains,
        ]);
    }
}
