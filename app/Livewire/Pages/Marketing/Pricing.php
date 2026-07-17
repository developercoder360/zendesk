<?php

namespace App\Livewire\Pages\Marketing;

use App\Models\Package;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.marketing')]
#[Title('Pricing | Zendesk')]
class Pricing extends Component
{
    public bool $annual = false;

    public function render()
    {
        return view('livewire.pages.marketing.pricing', [
            'packages' => Package::where('is_active', true)->get()->groupBy('name'),
        ]);
    }

    public function priceFor(Collection $variants): ?Package
    {
        return $variants->firstWhere('billing_interval', $this->annual ? 'yearly' : 'monthly')
            ?? $variants->first();
    }

    public function formatFeatureFlags(?array $flags): array
    {
        $formatted = [];
        foreach ((array) ($flags ?? []) as $key => $value) {
            $formattedKey = ucwords(str_replace('_', ' ', $key));
            if (is_array($value)) {
                $formatted[] = $formattedKey . ': ' . implode(', ', $value);
            } elseif (is_bool($value)) {
                if ($value) {
                    $formatted[] = $formattedKey;
                }
            } else {
                $formatted[] = $formattedKey . ': ' . ucfirst((string) $value);
            }
        }
        return $formatted;
    }

    public function featuresFor(Package $package): array
    {
        $features = [
            $package->agent_limit . ' ' . Str::plural('agent', $package->agent_limit),
            number_format($package->chat_limit_monthly) . ' chats/month',
        ];

        if ($package->ai_mode_allowed) {
            $features[] = 'AI mode enabled';
        }

        return array_merge($features, $this->formatFeatureFlags($package->feature_flags));
    }

    public function comparisonFeatures(): array
    {
        return Package::where('is_active', true)->get()
            ->flatMap(fn (Package $package) => $this->formatFeatureFlags($package->feature_flags))
            ->unique()
            ->values()
            ->all();
    }
}
