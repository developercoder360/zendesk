<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Department extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['tenant_id', 'name'];

    public function tenantUsers()
    {
        return $this->hasMany(TenantUser::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
