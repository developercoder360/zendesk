<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EmailTemplate;
use App\Models\User;

class EmailTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function view(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->tenant_id === $emailTemplate->tenant_id && $user->hasRole('Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function update(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->tenant_id === $emailTemplate->tenant_id && $user->hasRole('Admin');
    }

    public function delete(User $user, EmailTemplate $emailTemplate): bool
    {
        return $user->tenant_id === $emailTemplate->tenant_id && $user->hasRole('Admin');
    }
}
