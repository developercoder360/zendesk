<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Global Permissions (No tenant_id on permissions table by default in Spatie)
        $permissions = [
            // Dashboard
            'view_dashboard',

            // Company
            'view_company', 'edit_company', 'delete_company',

            // Users
            'view_users', 'create_users', 'edit_users', 'delete_users', 'invite_users',

            // Teams
            'view_teams', 'create_teams', 'edit_teams', 'delete_teams',

            // Tickets
            'view_tickets', 'create_tickets', 'assign_tickets', 'edit_tickets', 'close_tickets', 'delete_tickets',

            // Customers
            'view_customers', 'create_customers', 'edit_customers', 'delete_customers',

            // Knowledge Base
            'view_articles', 'create_articles', 'edit_articles', 'publish_articles', 'delete_articles',

            // Billing
            'view_billing', 'manage_subscription', 'download_invoices', 'upgrade_package',

            // Settings
            'view_settings', 'edit_settings',

            // Reports
            'view_reports', 'export_reports',

            // Notifications
            'view_notifications', 'send_notifications',

            // API
            'view_api_keys', 'create_api_keys', 'revoke_api_keys',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create Central Roles (tenant_id = null)
        // Temporarily unset team ID to create global roles
        setPermissionsTeamId(null);

        // Super Admin gets everything (handled via Gate::before in AppServiceProvider, but we can assign anyway)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'tenant_id' => null]);

        $platformAdmin = Role::firstOrCreate(['name' => 'Platform Admin', 'tenant_id' => null]);
        $platformAdmin->givePermissionTo([
            'view_dashboard', 'view_company', 'edit_company', 'view_users', 'view_billing', 'manage_subscription', 'view_settings', 'edit_settings',
        ]);

        $billingManager = Role::firstOrCreate(['name' => 'Billing Manager', 'tenant_id' => null]);
        $billingManager->givePermissionTo([
            'view_dashboard', 'view_billing', 'manage_subscription', 'download_invoices', 'upgrade_package',
        ]);

        $readOnlyAdmin = Role::firstOrCreate(['name' => 'Read Only Admin', 'tenant_id' => null]);
        $readOnlyAdmin->givePermissionTo([
            'view_dashboard', 'view_company', 'view_users', 'view_teams', 'view_tickets', 'view_customers', 'view_articles', 'view_billing', 'view_settings', 'view_reports', 'view_notifications',
        ]);
    }
}
