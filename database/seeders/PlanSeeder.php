<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Support Team',
                'slug' => 'support-team',
                'description' => 'For small teams getting started.',
                'price' => 19,
                'price_yearly' => 15, // Approx 20% off
                'features' => [
                    'Ticketing system',
                    'Email, X, Facebook',
                    'Basic analytics'
                ],
                'is_popular' => false,
                'is_active' => true,
                'cta_text' => 'Start free trial',
                'cta_link' => '/register'
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For growing businesses.',
                'price' => 69,
                'price_yearly' => 55, // Exact 20% off from UI
                'features' => [
                    'Everything in Support Team',
                    'Custom business rules',
                    'CSAT surveys',
                    'Multilingual support'
                ],
                'is_popular' => true,
                'is_active' => true,
                'cta_text' => 'Start free trial',
                'cta_link' => '/register'
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large-scale organizations.',
                'price' => 145,
                'price_yearly' => 115, // Exact 20% off from UI
                'features' => [
                    'Everything in Professional',
                    'Custom roles & permissions',
                    'Advanced AI features',
                    '24/7 priority support'
                ],
                'is_popular' => false,
                'is_active' => true,
                'cta_text' => 'Contact Sales',
                'cta_link' => '/contact'
            ],
        ];

        foreach ($plans as $plan) {
            \App\Models\Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
