<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class EmailTemplate extends Model
{
    use BelongsToTenant;

    protected $guarded = [];
}
