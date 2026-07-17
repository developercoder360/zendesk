<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\RegistrationDTO;
use App\Events\TenantRegistered;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterTenant
{
    public function execute(RegistrationDTO $dto): Tenant
    {
        return DB::transaction(function () use ($dto) {
            $tenant = Tenant::create([
                'name' => $dto->companyName,
                'subdomain' => $dto->companySlug,
                'company_name' => $dto->companyName,
                'phone' => $dto->phone,
                'country' => $dto->country,
                'timezone' => $dto->timezone,
                'package_id' => $dto->packageId,
            ]);

            $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';

            Domain::create([
                'domain' => $dto->companySlug . '.' . $centralDomain,
                'tenant_id' => $tenant->id,
            ]);

            $owner = User::create([
                'name' => $dto->ownerName,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'tenant_id' => $tenant->id,
            ]);

            // Set Spatie Team ID for this tenant temporarily to create roles
            $previousTeamId = getPermissionsTeamId();
            setPermissionsTeamId($tenant->id);

            $ownerRole = Role::create(['name' => 'Owner', 'tenant_id' => $tenant->id]);
            // Owner gets everything implicitly via Gate::before, but good practice to attach all current permissions
            $ownerRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());

            $adminRole = Role::create(['name' => 'Company Admin', 'tenant_id' => $tenant->id]);
            $adminRole->givePermissionTo([
                'view_dashboard', 'view_company', 'edit_company', 'view_users', 'create_users', 'edit_users', 'invite_users', 'view_teams', 'create_teams', 'edit_teams', 'view_tickets', 'create_tickets', 'assign_tickets', 'edit_tickets', 'close_tickets', 'delete_tickets', 'view_customers', 'create_customers', 'edit_customers', 'delete_customers', 'view_articles', 'create_articles', 'edit_articles', 'publish_articles', 'view_billing', 'manage_subscription', 'download_invoices', 'upgrade_package', 'view_settings', 'edit_settings', 'view_reports', 'export_reports', 'view_notifications', 'send_notifications', 'view_api_keys', 'create_api_keys', 'revoke_api_keys'
            ]);

            Role::create(['name' => 'Agent', 'tenant_id' => $tenant->id])->givePermissionTo([
                'view_dashboard', 'view_tickets', 'create_tickets', 'edit_tickets', 'close_tickets', 'view_customers', 'view_articles'
            ]);

            Role::create(['name' => 'Viewer', 'tenant_id' => $tenant->id])->givePermissionTo([
                'view_dashboard', 'view_tickets', 'view_customers', 'view_articles'
            ]);

            $owner->assignRole($ownerRole);

            // Restore previous team id
            setPermissionsTeamId($previousTeamId);

            TenantRegistered::dispatch($tenant, $owner);

            return $tenant;
        });
    }
}
