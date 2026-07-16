<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\TenantUser;
use Illuminate\Support\Facades\DB;

class ChatAssignmentService
{
    /**
     * Attempts to atomically claim a chat for an agent.
     * 
     * @param Chat $chat
     * @param TenantUser $agent
     * @return bool True if claim succeeded, false if already claimed.
     */
    public function claim(Chat $chat, TenantUser $agent): bool
    {
        return DB::transaction(function () use ($chat, $agent) {
            // Lock the chat row for update
            $lockedChat = Chat::where('id', $chat->id)->lockForUpdate()->first();

            if (!$lockedChat || $lockedChat->assigned_agent_id !== null) {
                return false;
            }

            // Database layer defense in depth: Atomic update checking assigned_agent_id IS NULL
            $updatedRows = DB::table('chats')
                ->where('id', $chat->id)
                ->whereNull('assigned_agent_id')
                ->update(['assigned_agent_id' => $agent->id]);

            return $updatedRows === 1;
        });
    }
}
