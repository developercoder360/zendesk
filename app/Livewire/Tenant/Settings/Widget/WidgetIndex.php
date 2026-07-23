<?php

namespace App\Livewire\Tenant\Settings\Widget;

use App\Models\WidgetSetting;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant')]
#[Title('Widget Settings')]
class WidgetIndex extends Component
{
    public string $primary_color = '#0f172a';
    public string $welcome_text = 'Hi there! How can we help you today?';
    public string $offline_message = 'We are currently offline. Please leave a message!';
    public array $allowed_domains = [];
    public string $new_domain = '';
    public string $embed_key = '';

    public function mount()
    {
        $tenantId = tenant('id');
        $setting = WidgetSetting::firstOrCreate(
            ['tenant_id' => $tenantId],
            [
                'primary_color' => '#0f172a',
                'welcome_text' => 'Hi there! How can we help you today?',
                'offline_message' => 'We are currently offline. Please leave a message!',
                'embed_key' => Str::random(40),
                'allowed_domains' => [],
            ]
        );

        $this->primary_color = $setting->primary_color ?? '#0f172a';
        $this->welcome_text = $setting->welcome_text ?? 'Hi there! How can we help you today?';
        $this->offline_message = $setting->offline_message ?? 'We are currently offline. Please leave a message!';
        $this->embed_key = $setting->embed_key;
        $this->allowed_domains = is_array($setting->allowed_domains) ? $setting->allowed_domains : [];
    }

    public function addDomain()
    {
        $domain = strtolower(trim($this->new_domain));

        // Strip protocol if user pasted http:// or https://
        $domain = preg_replace('#^https?://#', '', $domain);
        $domain = rtrim($domain, '/');

        if (empty($domain)) {
            return;
        }

        // Validate domain format (allows domains like example.com, sub.domain.co.uk, localhost, localhost:8000)
        $domainRegex = '/^(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}|localhost(?::\d+)?$/';
        if (!preg_match($domainRegex, $domain)) {
            $this->addError('new_domain', 'Please enter a valid domain (e.g. example.com or app.example.com).');
            return;
        }

        if (in_array($domain, $this->allowed_domains)) {
            $this->addError('new_domain', 'Domain is already added.');
            return;
        }

        $this->allowed_domains[] = $domain;
        $this->new_domain = '';
        $this->resetValidation('new_domain');
    }

    public function removeDomain(int $index)
    {
        if (isset($this->allowed_domains[$index])) {
            unset($this->allowed_domains[$index]);
            $this->allowed_domains = array_values($this->allowed_domains);
        }
    }

    public function regenerateKey()
    {
        $setting = WidgetSetting::where('tenant_id', tenant('id'))->first();
        if ($setting) {
            $newKey = Str::random(40);
            $setting->update(['embed_key' => $newKey]);
            $this->embed_key = $newKey;
            $this->dispatch('toast', message: 'Embed key regenerated. Please update your website embed snippet.', type: 'warning');
        }
    }

    public function save()
    {
        $this->validate([
            'primary_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'welcome_text' => ['required', 'string', 'max:255'],
            'offline_message' => ['nullable', 'string', 'max:500'],
            'allowed_domains' => ['array'],
        ], [
            'primary_color.regex' => 'The brand color must be a valid 6-character hex code (e.g. #0f172a).',
        ]);

        $setting = WidgetSetting::where('tenant_id', tenant('id'))->first();
        if ($setting) {
            $setting->update([
                'primary_color' => $this->primary_color,
                'welcome_text' => $this->welcome_text,
                'offline_message' => $this->offline_message,
                'allowed_domains' => array_values($this->allowed_domains),
            ]);
        }

        $this->dispatch('toast', message: 'Widget settings saved successfully.', type: 'success');
    }

    public function render()
    {
        return view('livewire.tenant.settings.widget.widget-index');
    }
}
