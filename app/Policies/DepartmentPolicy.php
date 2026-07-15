<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Agent');
    }

    public function view(User $user, Department $department): bool
    {
        return $user->tenant_id === $department->tenant_id && ($user->hasRole('Admin') || $user->hasRole('Agent'));
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function update(User $user, Department $department): bool
    {
        return $user->tenant_id === $department->tenant_id && $user->hasRole('Admin');
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->tenant_id === $department->tenant_id && $user->hasRole('Admin');
    }
}
