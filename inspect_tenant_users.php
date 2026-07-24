<?php

use App\Models\Tenant;
use App\Models\TenantUser;

$tenant = Tenant::find('63c67449-5dee-48e7-b625-149cbbdb9926');
tenancy()->initialize($tenant);

$list = TenantUser::with('user')->get()->map(function($tu) {
    return [
        'tenant_user_id' => $tu->id,
        'tenant_id' => $tu->tenant_id,
        'user_id' => $tu->user_id,
        'email' => $tu->user?->email,
        'name' => $tu->name,
        'is_ai' => $tu->is_ai ? 'YES' : 'NO',
        'created_at' => (string)$tu->created_at,
    ];
});

echo json_encode($list, JSON_PRETTY_PRINT) . "\n";
