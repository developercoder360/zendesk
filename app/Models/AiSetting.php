<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class AiSetting extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id', 'ai_mode_enabled', 'fallback_to_human', 'welcome_message', 'model_version',
    ];

    protected $casts = [
        'ai_mode_enabled' => 'boolean',
        'fallback_to_human' => 'boolean',
    ];
}
