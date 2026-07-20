<?php

use App\Models\Domain;
use App\Models\Package;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

it('can register a new tenant and provision defaults', function () {
    $package = Package::create(['name' => 'Pro', 'price' => 4900, 'billing_interval' => 'monthly', 'agent_limit' => 5, 'chat_limit_monthly' => 5000]);

    Volt::test('pages.auth.index')
        ->set('step', 'form')
        ->set('packageId', $package->id)
        ->set('companyName', 'Acme Corp')
        ->set('companySlug', 'acme')
        ->set('ownerName', 'John Doe')
        ->set('email', 'john@acme.com')
        ->set('password', 'password123A!')
        ->set('password_confirmation', 'password123A!')
        ->call('submitRegistrationForm')
        ->assertHasNoErrors()
        ->set('paymentMethodId', 'tok_mock_success')
        ->call('processPayment')
        ->assertHasNoErrors();

    $tenant = Tenant::latest()->first();
    expect($tenant->name)->toBe('Acme Corp');

    $domain = Domain::where('tenant_id', $tenant->id)->first();
    $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';
    expect($domain->domain)->toBe('acme.'.$centralDomain);

    $owner = User::where('email', 'john@acme.com')->first();
    expect($owner->tenant_id)->toBe($tenant->id);

    $this->assertDatabaseHas('roles', [
        'name' => 'Company Admin',
        'tenant_id' => $tenant->id,
    ]);

    // $this->assertDatabaseHas('departments', [
    //     'name' => 'General Support',
    //     'tenant_id' => $tenant->id,
    // ]);
});

it('rejects registration if payment fails', function () {
    $package = Package::create(['name' => 'Pro', 'price' => 4900, 'billing_interval' => 'monthly', 'agent_limit' => 5, 'chat_limit_monthly' => 5000]);

    Volt::test('pages.auth.index')
        ->set('step', 'form')
        ->set('packageId', $package->id)
        ->set('companyName', 'Fail Corp')
        ->set('companySlug', 'fail')
        ->set('ownerName', 'Fail Doe')
        ->set('email', 'fail@acme.com')
        ->set('password', 'password123A!')
        ->set('password_confirmation', 'password123A!')
        ->call('submitRegistrationForm')
        ->assertHasNoErrors()
        ->set('paymentMethodId', 'tok_fail')
        ->call('processPayment')
        ->assertHasErrors(['paymentError']);

    $domain = Domain::where('domain', 'fail.zendesk.test')->first();
    expect($domain)->toBeNull();
});
