<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'billing_interval', 'agent_limit', 'chat_limit_monthly',
        'ai_mode_allowed', 'feature_flags', 'is_active'
    ];

    protected $casts = [
        'feature_flags' => 'array',
        'ai_mode_allowed' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
