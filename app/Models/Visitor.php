<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Visitor extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'session_id',
        'served_by_agent_id',
        'name',
        'email',
        'ip_address',
        'current_page_url',
        'current_page_title',
        'open_tabs_count',
        'referrer',
        'visits_count',
        'country_code',
        'device_type',
        'browser',
        'status',
        'first_seen_at',
        'last_seen_at',
        'is_banned',
        'banned_at',
        'ban_reason',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'banned_at' => 'datetime',
        'is_banned' => 'boolean',
        'open_tabs_count' => 'integer',
        'visits_count' => 'integer',
    ];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function servedByAgent()
    {
        return $this->belongsTo(TenantUser::class, 'served_by_agent_id');
    }
}
