<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\TenantRegistered;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SeedTenantDefaults
{
    public function handle(TenantRegistered $event): void
    {
        $tenantId = $event->tenant->id;

        // Temporarily set the permissions team ID to the new tenant so the role assigns correctly
        setPermissionsTeamId($tenantId);

        // Roles & Permissions are now handled natively inside RegisterTenant action.

        // 2. Departments
        // DB::table('departments')->insert([
        //     ['tenant_id' => $tenantId, 'name' => 'General Support', 'description' => 'Default support department', 'created_at' => now(), 'updated_at' => now()],
        //     ['tenant_id' => $tenantId, 'name' => 'Billing', 'description' => 'Billing related inquiries', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        // 3. Ticket Statuses
        DB::table('ticket_statuses')->insert([
            ['tenant_id' => $tenantId, 'name' => 'Open', 'color' => '#ef4444', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['tenant_id' => $tenantId, 'name' => 'Pending', 'color' => '#eab308', 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
            ['tenant_id' => $tenantId, 'name' => 'Resolved', 'color' => '#22c55e', 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Email Templates
        DB::table('email_templates')->insert([
            ['tenant_id' => $tenantId, 'name' => 'Ticket Received', 'subject' => 'We received your request', 'body' => 'Thank you for reaching out. A ticket has been created.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
