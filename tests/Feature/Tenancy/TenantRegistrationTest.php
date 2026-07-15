<?php

use App\Models\User;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\Plan;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
});

it('can register a new tenant and provision defaults', function () {
    $plan = Plan::create(['name' => 'Pro', 'slug' => 'pro', 'price' => 4900]);

    Volt::test('pages.auth.register')
        ->set('step', 'form')
        ->set('planId', $plan->id)
        ->set('companyName', 'Acme Corp')
        ->set('companySlug', 'acme')
        ->set('ownerName', 'John Doe')
        ->set('email', 'john@acme.com')
        ->set('password', 'password123A!')
        ->set('password_confirmation', 'password123A!')
        ->call('submitForm')
        ->assertHasNoErrors()
        ->set('paymentMethodId', 'tok_mock_success')
        ->call('processPayment')
        ->assertHasNoErrors();

    $tenant = Tenant::latest()->first();
    expect($tenant->company_name)->toBe('Acme Corp');

    $domain = Domain::where('tenant_id', $tenant->id)->first();
    expect($domain->domain)->toBe('acme.zendesk.test');

    $owner = User::where('email', 'john@acme.com')->first();
    expect($owner->tenant_id)->toBe($tenant->id);

    $this->assertDatabaseHas('roles', [
        'name' => 'Admin',
        'tenant_id' => $tenant->id,
    ]);

    $this->assertDatabaseHas('departments', [
        'name' => 'General Support',
        'tenant_id' => $tenant->id,
    ]);
});

it('rejects registration if payment fails', function () {
    $plan = Plan::create(['name' => 'Pro', 'slug' => 'pro', 'price' => 4900]);

    Volt::test('pages.auth.register')
        ->set('step', 'form')
        ->set('planId', $plan->id)
        ->set('companyName', 'Fail Corp')
        ->set('companySlug', 'fail')
        ->set('ownerName', 'Fail Doe')
        ->set('email', 'fail@acme.com')
        ->set('password', 'password123A!')
        ->set('password_confirmation', 'password123A!')
        ->call('submitForm')
        ->assertHasNoErrors()
        ->set('paymentMethodId', 'tok_fail')
        ->call('processPayment')
        ->assertHasErrors(['paymentError']);

    $domain = Domain::where('domain', 'fail.zendesk.test')->first();
    expect($domain)->toBeNull();
});
