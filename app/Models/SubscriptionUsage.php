<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionUsage extends Model
{
    use HasFactory;

    protected $table = 'subscription_usage';

    protected $fillable = [
        'tenant_id', 'agents_used', 'chats_used_this_period', 'period_resets_at',
    ];

    protected $casts = [
        'period_resets_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
