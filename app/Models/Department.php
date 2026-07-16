<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Department extends Model
{
    use BelongsToTenant;

    protected $guarded = [];
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

