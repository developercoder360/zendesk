<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Support Team',
                'agent_limit' => 3,
                'chat_limit_monthly' => 1000,
                'ai_mode_allowed' => false,
                'feature_flags' => [
                    'channels' => ['email', 'web_widget'],
                    'analytics' => 'basic',
                    'custom_business_rules' => false,
                    'csat_surveys' => false,
                ],
                'prices' => [
                    'monthly' => 19,
                    'yearly' => 15,
                ],
            ],
            [
                'name' => 'Professional',
                'agent_limit' => 10,
                'chat_limit_monthly' => 5000,
                'ai_mode_allowed' => true,
                'feature_flags' => [
                    'channels' => ['email', 'web_widget', 'facebook', 'x'],
                    'analytics' => 'advanced',
                    'custom_business_rules' => true,
                    'csat_surveys' => true,
                    'multilingual' => true,
                ],
                'prices' => [
                    'monthly' => 69,
                    'yearly' => 55,
                ],
            ],
            [
                'name' => 'Enterprise',
                'agent_limit' => 50,
                'chat_limit_monthly' => 20000,
                'ai_mode_allowed' => true,
                'feature_flags' => [
                    'channels' => ['email', 'web_widget', 'facebook', 'x'],
                    'analytics' => 'advanced',
                    'custom_business_rules' => true,
                    'csat_surveys' => true,
                    'multilingual' => true,
                    'custom_roles' => true,
                    'priority_support' => true,
                ],
                'prices' => [
                    'monthly' => 145,
                    'yearly' => 115,
                ],
            ],
        ];
        foreach ($packages as $package) {
            $prices = $package['prices'];
            unset($package['prices']);
            foreach ($prices as $interval => $price) {
                Package::firstOrCreate(
                    ['name' => $package['name'], 'billing_interval' => $interval],
                    array_merge($package, [
                        'price' => $price,
                        'billing_interval' => $interval,
                        'is_active' => true,
                    ])
                );
            }
        }
    }
}
