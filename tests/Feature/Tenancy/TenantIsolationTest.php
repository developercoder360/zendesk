<?php

use App\Models\User;
use App\Models\Tenant;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents users from seeing other tenant departments', function () {
    $tenant1 = Tenant::create(['id' => 'tenant-1']);
    $tenant2 = Tenant::create(['id' => 'tenant-2']);

    Department::create(['name' => 'Dept 1', 'tenant_id' => $tenant1->id]);
    Department::create(['name' => 'Dept 2', 'tenant_id' => $tenant2->id]);
    
    tenancy()->initialize($tenant1);
    
    $departments = Department::all();
    
    expect($departments)->toHaveCount(1);
    expect($departments->first()->name)->toBe('Dept 1');
    
    tenancy()->end();
});
