<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TicketStatus;
use App\Models\User;

class TicketStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Agent');
    }

    public function view(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->tenant_id === $ticketStatus->tenant_id && ($user->hasRole('Admin') || $user->hasRole('Agent'));
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    public function update(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->tenant_id === $ticketStatus->tenant_id && $user->hasRole('Admin');
    }

    public function delete(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->tenant_id === $ticketStatus->tenant_id && $user->hasRole('Admin');
    }
}
