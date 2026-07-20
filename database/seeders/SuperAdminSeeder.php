<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('super_admin.email', 'admin@zendesk.test');
        $password = config('super_admin.password', 'password123!');
        $name = config('super_admin.name', 'Super Admin');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        setPermissionsTeamId(null); // Ensure we're in central context

        // Ensure the role exists
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'tenant_id' => null]);

        if (! $user->hasRole('Super Admin')) {
            $user->assignRole($role);
        }
    }
}
