<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Activity extends SpatieActivity
{
    use BelongsToTenant;
}
