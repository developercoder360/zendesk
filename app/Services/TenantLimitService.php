<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantLimitService
{
    /**
     * Checks if a tenant can invite a new agent based on their active package limit.
     */
    public function canInviteAgent(Tenant $tenant): bool
    {
        return DB::transaction(function () use ($tenant) {
            // Note: Assumes subscription_usage and package tables exist as per Phase 1 specs
            $usage = DB::table('subscription_usage')
                ->where('tenant_id', $tenant->id)
                ->lockForUpdate()
                ->first();

            if (! $usage) {
                return false;
            }

            $package = DB::table('packages')->where('id', $usage->package_id)->first();

            if (! $package) {
                return false;
            }

            return $usage->agents_used < $package->agent_limit;
        });
    }

    /**
     * Increment agent usage atomically.
     */
    public function incrementAgentUsage(Tenant $tenant): void
    {
        DB::table('subscription_usage')
            ->where('tenant_id', $tenant->id)
            ->increment('agents_used');
    }

    /**
     * Checks if a tenant can start a new chat based on their active package limit.
     */
    public function canStartNewChat(Tenant $tenant): bool
    {
        return DB::transaction(function () use ($tenant) {
            $usage = DB::table('subscription_usage')
                ->where('tenant_id', $tenant->id)
                ->lockForUpdate()
                ->first();

            if (! $usage) {
                return false;
            }

            $package = DB::table('packages')->where('id', $usage->package_id)->first();

            if (! $package) {
                return false;
            }

            return $usage->chats_used_this_period < $package->chat_limit_monthly;
        });
    }

    /**
     * Increment chat usage atomically.
     */
    public function incrementChatUsage(Tenant $tenant): void
    {
        DB::table('subscription_usage')
            ->where('tenant_id', $tenant->id)
            ->increment('chats_used_this_period');
    }
}
