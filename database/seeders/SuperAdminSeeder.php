<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('SUPER_ADMIN_EMAIL', 'admin@zendesk.test');
        $password = env('SUPER_ADMIN_PASSWORD', 'password123!');
        $name = env('SUPER_ADMIN_NAME', 'Super Admin');

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
        
        if (!$user->hasRole('Super Admin')) {
            $user->assignRole($role);
        }
    }
}
