<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'tenant_id',
        'subject',
        'description',
        'priority',
        'status_id',
        'department_id',
        'user_id',
        'customer_id',
        'agent_id',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
