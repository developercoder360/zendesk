<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant
{
    use HasDomains;

    protected $casts = [
        'data' => 'array',
    ];
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

