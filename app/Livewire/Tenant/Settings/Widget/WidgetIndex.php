<?php

namespace App\Livewire\Tenant\Settings\Widget;

use App\Models\WidgetSetting;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class WidgetIndex extends Component
{
    public $primary_color = '#0f172a';
    public $welcome_text = 'Hi there! How can we help you today?';
    public $offline_message = 'We are currently offline. Please leave a message!';
    public $allowed_domains_text = '';
    public $embed_key = '';

    public $saved = false;

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
        $this->allowed_domains_text = is_array($setting->allowed_domains) ? implode("\n", $setting->allowed_domains) : '';
    }

    public function regenerateKey()
    {
        $setting = WidgetSetting::where('tenant_id', tenant('id'))->first();
        if ($setting) {
            $setting->update(['embed_key' => Str::random(40)]);
            $this->embed_key = $setting->embed_key;
        }
    }

    public function save()
    {
        $this->validate([
            'primary_color' => 'required|string|max:20',
            'welcome_text' => 'required|string|max:255',
            'offline_message' => 'nullable|string|max:500',
            'allowed_domains_text' => 'nullable|string',
        ]);

        $domains = array_filter(array_map('trim', explode("\n", $this->allowed_domains_text)));

        $setting = WidgetSetting::where('tenant_id', tenant('id'))->first();
        if ($setting) {
            $setting->update([
                'primary_color' => $this->primary_color,
                'welcome_text' => $this->welcome_text,
                'offline_message' => $this->offline_message,
                'allowed_domains' => array_values($domains),
            ]);
        }

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.tenant.settings.widget.widget-index');
    }
}
