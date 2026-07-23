<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Chat extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id', 'visitor_id', 'assigned_agent_id', 'department_id', 'status', 'handled_by', 'needs_human_escalation', 'is_typing',
    ];

    protected $casts = [
        'needs_human_escalation' => 'boolean',
        'is_typing' => 'boolean',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function agent()
    {
        return $this->belongsTo(TenantUser::class, 'assigned_agent_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
