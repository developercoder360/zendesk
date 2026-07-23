<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class CannedResponse extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id', 'tenant_user_id', 'shortcut_key', 'title', 'body', 'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function tenantUser()
    {
        return $this->belongsTo(TenantUser::class);
    }
}
