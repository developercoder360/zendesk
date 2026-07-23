<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class TenantUser extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'department_id',
        'shift',
        'status',
        'position',
        'phone',
        'avatar',
        'is_active',
        'is_ai',
        'joined_at',
        'notification_preferences',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_ai' => 'boolean',
        'joined_at' => 'datetime',
        'notification_preferences' => 'array',
    ];

    public function getNameAttribute(): string
    {
        if ($this->is_ai) {
            return 'AI Assistant';
        }
        return $this->user?->name ?? 'Agent';
    }

    public static function getAiAssistantUser(?string $tenantId = null): self
    {
        $tid = $tenantId ?? tenant('id');
        return self::firstOrCreate(
            ['tenant_id' => $tid, 'is_ai' => true],
            [
                'user_id' => null,
                'status' => 'online',
                'is_active' => true,
                'position' => 'AI Support Assistant',
            ]
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedChats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Chat::class, 'assigned_agent_id');
    }
}