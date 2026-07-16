<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class CannedResponse extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'tenant_user_id', 'shortcut_key', 'title', 'body'
    ];

    public function tenantUser()
    {
        return $this->belongsTo(TenantUser::class);
    }
}
