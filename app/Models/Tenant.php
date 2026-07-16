<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $fillable = [
        'id', 'name', 'subdomain', 'status', 'trial_ends_at'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscriptionUsage()
    {
        return $this->hasOne(SubscriptionUsage::class);
    }
}
