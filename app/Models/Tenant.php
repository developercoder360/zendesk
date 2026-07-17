<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant
{
    use HasDomains;

    protected $fillable = [
        'id', 'name', 'subdomain', 'status', 'trial_ends_at'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'subdomain',
            'status',
            'trial_ends_at',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscriptionUsage()
    {
        return $this->hasOne(SubscriptionUsage::class);
    }
}
