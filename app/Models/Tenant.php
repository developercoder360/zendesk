<?php

namespace App\Models;

use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasDomains;

    protected $fillable = [
        'id', 'name', 'subdomain', 'status', 'trial_ends_at',
        'company_name', 'phone', 'country', 'timezone', 'package_id',
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

    public function getPackageAttribute()
    {
        return Package::find($this->package_id);
    }

    public function canAddSubdomain(): bool
    {
        $limit = $this->package?->max_subdomains;
        if (is_null($limit)) return true;
        return $this->domains()->count() < $limit;
    }

    public function remainingSubdomainSlots(): ?int
    {
        $limit = $this->package?->max_subdomains;
        if (is_null($limit)) return null;
        return max(0, $limit - $this->domains()->count());
    }

    public function primaryDomain()
    {
        return $this->domains()->where('is_primary', true)->first() 
            ?? $this->domains()->orderBy('created_at')->first();
    }
}
