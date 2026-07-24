<?php

namespace App\Livewire\Public\Widget;

use App\Models\TenantUser;
use App\Models\Visitor;
use App\Models\Chat;
use App\Models\Message;
use App\Models\WidgetSetting;
use App\Jobs\ProcessAiChatResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.widget')]
class TicketForm extends Component
{
    // Widget Configuration & Security
    public bool $isNotConfigured = false;
    public bool $isBlocked = false;
    public string $statusMessage = '';
    public string $primaryColor = '#6366f1';
    public string $welcomeText = 'Hi there! How can we help you today?';
    public string $offlineMessage = 'Our team is currently offline. Please leave a message!';

    // Mode & UI State (Assigned ONCE at mount(), stable throughout lifecycle)
    public string $mode = 'chat'; // 'chat' | 'offline_form'
    public bool $isOpen = true;
    public string $wsession = '';

    // Live Chat Mode Properties
    public ?int $visitorId = null;
    public ?int $chatId = null;
    public string $newMessageBody = '';
    public bool $isTyping = false;
    public int $unreadCount = 0;

    // Offline Form Mode Properties
    #[Validate('required_if:mode,offline_form|nullable|string|max:255')]
    public string $name = '';

    #[Validate('required_if:mode,offline_form|nullable|email|max:255')]
    public string $email = '';

    #[Validate('required_if:mode,offline_form|nullable|string|max:255')]
    public string $subject = '';

    #[Validate('required_if:mode,offline_form|nullable|string')]
    public string $description = '';

    public bool $formSubmitted = false;

    public function mount()
    {
        $tenantId = tenant('id');
        $embedKey = request()->query('key');
        
        // 1. Resolve Widget Settings
        $setting = null;
        if ($embedKey) {
            $setting = WidgetSetting::where('embed_key', $embedKey)->first();
            if (!$setting) {
                $this->isNotConfigured = true;
                $this->statusMessage = "This widget embed key is invalid or has been regenerated. Please update your website embed snippet.";
                return;
            }
        } elseif ($tenantId) {
            $setting = WidgetSetting::where('tenant_id', $tenantId)->first();
        }

        if (!$setting) {
            $this->isNotConfigured = true;
            $this->statusMessage = "This widget hasn't been configured yet. Add an allowed domain in your Widget settings to activate it.";
            return;
        }

        $this->primaryColor = $setting->primary_color ?? '#6366f1';
        $this->welcomeText = $setting->welcome_text ?? 'Hi there! How can we help you today?';
        $this->offlineMessage = $setting->offline_message ?? 'Our team is currently offline. Please leave a message!';

        // 2. Strict Block-by-Default Domain Validation
        $allowedDomains = is_array($setting->allowed_domains) ? $setting->allowed_domains : [];
        if (empty($allowedDomains)) {
            $this->isNotConfigured = true;
            $this->statusMessage = "This widget hasn't been configured yet. Add an allowed domain in your Widget settings to activate it.";
            return;
        }

        $referer = request()->headers->get('referer') ?? request()->headers->get('origin');
        $host = '';
        $hostWithPort = '';
        if ($referer) {
            $parsedUrl = parse_url($referer);
            $host = $parsedUrl['host'] ?? '';
            $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
            $hostWithPort = $host . $port;
        }

        $matched = false;
        if (!empty($host)) {
            foreach ($allowedDomains as $allowedDomain) {
                $allowedDomainClean = strtolower(trim($allowedDomain));
                if (strtolower($host) === $allowedDomainClean || strtolower($hostWithPort) === $allowedDomainClean) {
                    $matched = true;
                    break;
                }
            }
        } else {
            // Public embed requires a valid Referer/Origin header matching allowed_domains
            $matched = false;
        }

        if (!$matched) {
            $this->isBlocked = true;
            $displayOrigin = $hostWithPort ?: 'Unknown Origin';
            $this->statusMessage = "Access Restricted: The domain ({$displayOrigin}) is not authorized to display this chat widget.";
            return;
        }

        // 3. Visitor Session Resolution (localStorage token via URL fallback)
        $this->wsession = request()->query('wsession') ?: Str::uuid()->toString();

        if ($tenantId && $this->wsession) {
            $visitor = Visitor::where('tenant_id', $tenantId)->where('session_id', $this->wsession)->first();
            if ($visitor) {
                $this->visitorId = $visitor->id;
                $chat = Chat::where('tenant_id', $tenantId)->where('visitor_id', $visitor->id)->where('status', 'open')->latest()->first();
                if ($chat) {
                    $this->chatId = $chat->id;
                }
            }
        }

        // 4. Availability Check & Stable Mode Determination (ONCE at mount)
        $humanOnline = TenantUser::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->where('status', 'online')
            ->where(function ($q) {
                $q->where('is_ai', false)->orWhereNull('is_ai');
            })
            ->count() > 0;

        $hasAiKeys = !empty(env('GOOGLE_API_KEY')) || !empty(env('OPENROUTER_API_KEY'));
        $aiAvailable = false;
        if ($hasAiKeys) {
            // Cached RAG service reachability check (45s TTL) — NO uncached network call on every mount
            $aiAvailable = Cache::remember('rag_service_reachable_' . $tenantId, 45, function () {
                try {
                    $res = Http::timeout(2)->get(env('RAG_SERVICE_URL', 'http://rag:8080') . '/health');
                    return $res->successful();
                } catch (\Throwable $e) {
                    return false;
                }
            });
        }

        $this->mode = ($humanOnline || $aiAvailable) ? 'chat' : 'offline_form';
    }

    public function sendMessage()
    {
        if ($this->isNotConfigured || $this->isBlocked || $this->mode !== 'chat') {
            return;
        }

        $body = trim($this->newMessageBody);
        if (empty($body)) {
            return;
        }

        $tenantId = tenant('id');

        // Lazy Visitor Creation
        if (!$this->visitorId) {
            $visitor = Visitor::create([
                'tenant_id' => $tenantId,
                'session_id' => $this->wsession,
                'name' => 'Guest Visitor',
                'email' => null,
                'status' => 'online',
                'ip_address' => request()->ip(),
            ]);
            $this->visitorId = $visitor->id;
        }

        // Lazy Chat Creation
        if (!$this->chatId) {
            $chat = Chat::create([
                'tenant_id' => $tenantId,
                'visitor_id' => $this->visitorId,
                'status' => 'open',
            ]);
            $this->chatId = $chat->id;
        }

        // Create Visitor Message
        Message::create([
            'chat_id' => $this->chatId,
            'sender_id' => $this->visitorId,
            'sender_type' => Visitor::class,
            'body' => $body,
        ]);

        $this->newMessageBody = '';

        // Dispatch AI Auto-Reply Job
        ProcessAiChatResponse::dispatch($this->chatId, $body);
    }

    public function submitOfflineForm()
    {
        if ($this->isNotConfigured || $this->isBlocked || $this->mode !== 'offline_form') {
            return;
        }

        $this->validate();

        $tenantId = tenant('id');

        $visitor = Visitor::firstOrCreate(
            ['email' => $this->email, 'tenant_id' => $tenantId],
            ['name' => $this->name, 'session_id' => $this->wsession, 'ip_address' => request()->ip()]
        );
        $this->visitorId = $visitor->id;

        $chat = Chat::create([
            'tenant_id' => $tenantId,
            'visitor_id' => $visitor->id,
            'status' => 'open',
        ]);
        $this->chatId = $chat->id;

        $bodyText = "Subject: " . $this->subject . "\n\n" . $this->description;
        Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $visitor->id,
            'sender_type' => Visitor::class,
            'body' => $bodyText,
        ]);

        $this->reset(['name', 'email', 'subject', 'description']);
        $this->formSubmitted = true;
    }

    public function syncChat()
    {
        if (!$this->chatId || $this->mode !== 'chat') {
            return;
        }

        $chat = Chat::find($this->chatId);
        if ($chat) {
            $this->isTyping = (bool) $chat->is_typing;
        }
    }

    public function toggleWidget()
    {
        $this->isOpen = !$this->isOpen;
        $eventType = $this->isOpen ? 'openWidget' : 'closeWidget';
        $this->dispatch('postMessage', type: $eventType);
    }

    public function getMessagesProperty()
    {
        if (!$this->chatId) {
            return collect();
        }

        return Message::where('chat_id', $this->chatId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.public.widget.ticket-form');
    }
}
