<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ChatVisibilityScope extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['tenant_id', 'tenant_user_id', 'scope'];

    public function tenantUser()
    {
        return $this->belongsTo(TenantUser::class);
    }
}
