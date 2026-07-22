<?php

namespace App\Livewire\Tenant\Settings\Company;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class CompanyIndex extends Component
{
    public $company_name = '';
    public $phone = '';
    public $country = '';
    public $timezone = '';
    public $subdomain = '';

    public $saved = false;

    public function mount()
    {
        $tenant = tenant();

        $this->company_name = $tenant->company_name ?? $tenant->name ?? '';
        $this->phone = $tenant->phone ?? '';
        $this->country = $tenant->country ?? '';
        $this->timezone = $tenant->timezone ?? 'UTC';
        $this->subdomain = $tenant->subdomain ?? '';
    }

    public function save()
    {
        $tenant = tenant();

        $this->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'timezone' => 'required|string|max:100',
        ]);

        $tenant->update([
            'company_name' => $this->company_name,
            'name' => $this->company_name,
            'phone' => $this->phone,
            'country' => $this->country,
            'timezone' => $this->timezone,
        ]);

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.tenant.settings.company.company-index');
    }
}
