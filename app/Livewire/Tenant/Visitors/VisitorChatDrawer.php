<?php

namespace App\Livewire\Tenant\Visitors;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Visitor;
use App\Models\TenantUser;
use Livewire\Component;

class VisitorChatDrawer extends Component
{
    public bool $isOpen = false;
    public bool $isMinimized = false;
    public string $activeTab = 'current'; // 'current' | 'past'

    public ?int $activeVisitorId = null;
    public ?int $activeChatId = null;

    // Auto-open guard
    public array $dismissedVisitors = []; // [visitor_id => timestamp]

    // Form inputs for sending message and editing sidebar details
    public string $newMessageBody = '';
    public string $visitorName = '';
    public string $visitorEmail = '';
    public string $visitorPhone = '';
    public string $visitorNotes = '';
    public string $chatTags = '';

    public function mount()
    {
        $this->checkActiveVisitorSession();
    }

    /**
     * Stage 1: Ultra-lightweight check if active visitor sessions exist.
     * Runs via wire:poll.30s when closed. Uses select(['id', 'visitor_id']) without eager loading.
     */
    public function checkActiveVisitorSession()
    {
        $agentId = auth()->user()?->tenantProfile?->id;
        $tenantId = tenant('id');

        if (!$tenantId) {
            return;
        }

        // Stage 1: Minimal check (NO eager loading of visitor or messages)
        $latestOpenChat = Chat::where('tenant_id', $tenantId)
            ->where('status', 'open')
            ->where(function ($q) use ($agentId) {
                $q->whereNull('assigned_agent_id');
                if ($agentId) {
                    $q->orWhere('assigned_agent_id', $agentId);
                }
            })
            ->whereHas('messages')
            ->select(['id', 'visitor_id', 'updated_at'])
            ->latest('updated_at')
            ->first();

        if ($latestOpenChat) {
            $visitorId = $latestOpenChat->visitor_id;

            // Auto-open logic with 10-second re-open debounce guard
            $lastDismissed = $this->dismissedVisitors[$visitorId] ?? 0;
            $isGuardActive = (time() - $lastDismissed) < 10;

            if (!$this->isOpen && !$isGuardActive) {
                $this->isOpen = true;
            }

            if ($this->activeVisitorId !== $visitorId || $this->activeChatId !== $latestOpenChat->id) {
                $this->selectVisitor($visitorId, $latestOpenChat->id);
            }
        } else {
            if (!$this->isOpen) {
                $this->activeVisitorId = null;
                $this->activeChatId = null;
            }
        }
    }

    /**
     * Stage 2: Scope open polling strictly to refreshing the active chat
     */
    public function syncActiveChat()
    {
        if (!$this->isOpen || !$this->activeChatId) {
            $this->checkActiveVisitorSession();
            return;
        }
    }

    public function selectVisitor(int $visitorId, ?int $chatId = null)
    {
        $this->activeVisitorId = $visitorId;
        
        $chat = $chatId ? Chat::find($chatId) : Chat::where('visitor_id', $visitorId)->where('status', 'open')->latest()->first();
        $this->activeChatId = $chat?->id;

        $visitor = Visitor::find($visitorId);
        if ($visitor) {
            $this->loadVisitorDetails($visitor);
        }
    }

    private function loadVisitorDetails(Visitor $visitor)
    {
        $this->visitorName  = $visitor->name ?? '';
        $this->visitorEmail = $visitor->email ?? '';
        $this->visitorPhone = $visitor->phone ?? '';
        $this->visitorNotes = $visitor->notes ?? '';
        $this->chatTags     = $visitor->tags ?? '';
    }

    public function updatedVisitorName()
    {
        $this->saveVisitorField('name', $this->visitorName);
    }

    public function updatedVisitorEmail()
    {
        $this->saveVisitorField('email', $this->visitorEmail);
    }

    public function updatedVisitorPhone()
    {
        $this->saveVisitorField('phone', $this->visitorPhone);
    }

    public function updatedVisitorNotes()
    {
        $this->saveVisitorField('notes', $this->visitorNotes);
    }

    public function updatedChatTags()
    {
        $this->saveVisitorField('tags', $this->chatTags);
    }

    private function saveVisitorField(string $field, $value)
    {
        if ($this->activeVisitorId) {
            Visitor::where('id', $this->activeVisitorId)->update([$field => $value]);
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessageBody' => 'required|string|max:2000',
        ]);

        if (!$this->activeChatId) {
            return;
        }

        $agentProfile = auth()->user()?->tenantProfile;

        // Guard against missing TenantUser profile (preventing morph relationship corruption)
        if (!$agentProfile) {
            \Illuminate\Support\Facades\Log::error("Attempted to send visitor chat message but user has no TenantUser profile", [
                'user_id' => auth()->id(),
                'tenant_id' => tenant('id'),
            ]);
            $this->dispatch('toast', message: "Your agent profile could not be found — contact an admin.", type: 'error');
            return;
        }

        Message::create([
            'tenant_id'   => tenant('id'),
            'chat_id'     => $this->activeChatId,
            'body'        => trim($this->newMessageBody),
            'sender_type' => 'App\Models\TenantUser',
            'sender_id'   => $agentProfile->id,
        ]);

        // Design Decision: Auto-assign unassigned chat to the replying agent (standard Zendesk workflow).
        // Replying to an active visitor chat claims ownership of the chat for that agent.
        $chat = Chat::find($this->activeChatId);
        if ($chat && !$chat->assigned_agent_id) {
            $chat->update(['assigned_agent_id' => $agentProfile->id]);
        }

        $this->newMessageBody = '';
    }

    public function toggleMinimize()
    {
        $this->isMinimized = !$this->isMinimized;
    }

    public function closeDrawer()
    {
        if ($this->activeVisitorId) {
            $this->dismissedVisitors[$this->activeVisitorId] = time();
        }
        $this->isOpen = false;
    }

    public function openDrawer()
    {
        $this->isOpen = true;
        $this->isMinimized = false;
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $agentId = auth()->user()?->tenantProfile?->id;
        $tenantId = tenant('id');

        $activeChats = collect();
        $activeChat = null;
        $messages = collect();
        $pastChatsCount = 0;
        $visitor = null;

        // Stage 2: Only fetch full active chats and messages when drawer is open
        if ($this->isOpen && $tenantId) {
            $activeChats = Chat::where('tenant_id', $tenantId)
                ->where('status', 'open')
                ->where(function ($q) use ($agentId) {
                    $q->whereNull('assigned_agent_id');
                    if ($agentId) {
                        $q->orWhere('assigned_agent_id', $agentId);
                    }
                })
                ->select(['id', 'tenant_id', 'visitor_id', 'assigned_agent_id', 'status', 'updated_at'])
                ->with(['visitor:id,name,country_code,device_type,browser,status'])
                ->latest('updated_at')
                ->get();

            if ($this->activeVisitorId) {
                $visitor = Visitor::find($this->activeVisitorId);
                
                if ($visitor) {
                    $pastChatsCount = Chat::where('visitor_id', $visitor->id)
                        ->where('status', '!=', 'open')
                        ->count();
                }

                if ($this->activeChatId) {
                    $activeChat = Chat::where('id', $this->activeChatId)
                        ->with(['messages.sender'])
                        ->first();
                    $messages = $activeChat?->messages ?? collect();
                }
            }
        }

        return view('livewire.tenant.visitors.visitor-chat-drawer', [
            'activeChats'     => $activeChats,
            'activeChat'      => $activeChat,
            'messages'        => $messages,
            'visitor'         => $visitor,
            'pastChatsCount'  => $pastChatsCount,
        ]);
    }
}
