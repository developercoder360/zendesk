<?php

namespace App\Jobs;

use App\Models\Chat;
use App\Models\Message;
use App\Models\TenantUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ProcessAiChatResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $chatId;
    public string $visitorMessageText;

    public function __construct(int $chatId, string $visitorMessageText)
    {
        $this->chatId = $chatId;
        $this->visitorMessageText = $visitorMessageText;
    }

    public function handle(): void
    {
        $chat = Chat::find($this->chatId);
        if (!$chat || $chat->status !== 'open') {
            return;
        }

        // 1. Human Handoff Check: If a human agent claimed/assigned the chat, AI stops immediately
        if ($chat->assigned_agent_id !== null) {
            return;
        }

        // 2. Escalation Check: If chat is already marked for human escalation, AI stops
        if ($chat->needs_human_escalation) {
            return;
        }

        $tenantId = $chat->tenant_id;

        // 3. Trigger Condition (Default OR logic: No online human agents OR unanswered)
        $onlineAgentsCount = TenantUser::where('tenant_id', $tenantId)
            ->where('status', 'online')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('is_ai', false)->orWhereNull('is_ai');
            })
            ->count();

        // Check if unanswered timeout (or immediate if no online agents)
        $lastVisitorMsg = Message::where('chat_id', $chat->id)
            ->where('sender_type', 'App\Models\Visitor')
            ->latest()
            ->first();

        $unansweredMinutes = $lastVisitorMsg ? $lastVisitorMsg->created_at->diffInMinutes(now()) : 0;
        $shouldTrigger = ($onlineAgentsCount === 0) || ($unansweredMinutes >= 2) || ($chat->assigned_agent_id === null);

        if (!$shouldTrigger) {
            return;
        }

        // 4. Rate Limiting (Cost Control)
        $visitorKey = "ai-reply:visitor:{$chat->visitor_id}";
        $tenantKey  = "ai-reply:tenant:{$tenantId}:daily";

        if (RateLimiter::tooManyAttempts($visitorKey, 5)) {
            Log::warning("AI Auto-reply rate limit reached for visitor {$chat->visitor_id}");
            return;
        }

        if (RateLimiter::tooManyAttempts($tenantKey, 100)) {
            Log::warning("AI Auto-reply daily quota reached for tenant {$tenantId}");
            return;
        }

        RateLimiter::hit($visitorKey, 60);
        RateLimiter::hit($tenantKey, 86400);

        // 5. Visual Pending/Typing State
        $chat->update(['is_typing' => true]);

        // 6. Call RAG Microservice
        $ragUrl = env('RAG_SERVICE_URL', 'http://rag:8080');
        $similarityThreshold = (float) env('AI_RAG_SIMILARITY_THRESHOLD', 0.7);

        try {
            $response = Http::timeout(15)->post("{$ragUrl}/api/v1/chat", [
                'tenant_id'            => $tenantId,
                'message'              => $this->visitorMessageText,
                'similarity_threshold' => $similarityThreshold,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $replyText       = $data['reply'] ?? "I'm connecting you with a human support agent who can best assist you.";
                $needsEscalation = (bool) ($data['needs_escalation'] ?? false);

                // Fetch or create real AI Assistant TenantUser backing record
                $aiUser = TenantUser::getAiAssistantUser($tenantId);

                // Create AI Assistant Message referencing real backing TenantUser profile
                Message::create([
                    'tenant_id'   => $tenantId,
                    'chat_id'     => $chat->id,
                    'body'        => $replyText,
                    'sender_type' => 'App\Models\TenantUser',
                    'sender_id'   => $aiUser->id,
                ]);

                if ($needsEscalation) {
                    $chat->update([
                        'needs_human_escalation' => true,
                        'is_typing'              => false,
                    ]);
                } else {
                    $chat->update(['is_typing' => false]);
                }
            } else {
                Log::error("RAG Service HTTP error: " . $response->status());
                $chat->update(['is_typing' => false]);
            }
        } catch (\Throwable $e) {
            Log::error("RAG Service call exception: " . $e->getMessage());
            $chat->update(['is_typing' => false]);
        }
    }
}
