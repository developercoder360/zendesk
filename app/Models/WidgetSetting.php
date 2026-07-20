<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class WidgetSetting extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id', 'primary_color', 'welcome_text', 'embed_key', 'allowed_domains', 'business_hours', 'offline_message',
    ];

    protected $casts = [
        'allowed_domains' => 'array',
        'business_hours' => 'array',
    ];

    // TODO: Add rate_limited_at or request tracking concern logic for API rate limiting in future phase
}
