<?php

namespace App\Livewire\Public\Widget;

use App\Models\TenantUser;
use App\Models\Visitor;
use App\Models\Chat;
use App\Models\WidgetSetting;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.widget')]
class TicketForm extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:255')]
    public string $subject = '';

    #[Validate('required|string')]
    public string $description = '';

    public bool $success = false;
    public bool $isNotConfigured = false;
    public bool $isBlocked = false;
    public string $statusMessage = '';

    public string $primaryColor = '#0f172a';
    public string $welcomeText = 'Hi there! How can we help you today?';
    public string $offlineMessage = 'We are currently offline. Please leave a message!';
    public bool $isOffline = false;

    public function mount()
    {
        $embedKey = request()->query('key');
        
        $setting = null;
        if ($embedKey) {
            $setting = WidgetSetting::where('embed_key', $embedKey)->first();
            if (!$setting) {
                $this->isNotConfigured = true;
                $this->statusMessage = "This widget embed key is invalid or has been regenerated. Please update your website embed snippet.";
                return;
            }
        } elseif (tenant('id')) {
            $setting = WidgetSetting::where('tenant_id', tenant('id'))->first();
        }

        if (!$setting) {
            $this->isNotConfigured = true;
            $this->statusMessage = "This widget hasn't been configured yet. Add an allowed domain in your Widget settings to activate it.";
            return;
        }

        $this->primaryColor = $setting->primary_color ?? '#0f172a';
        $this->welcomeText = $setting->welcome_text ?? 'Hi there! How can we help you today?';
        $this->offlineMessage = $setting->offline_message ?? 'We are currently offline. Please leave a message!';

        $allowedDomains = is_array($setting->allowed_domains) ? $setting->allowed_domains : [];

        // BLOCK BY DEFAULT: If allowed_domains is empty, refuse to render for any origin
        if (empty($allowedDomains)) {
            $this->isNotConfigured = true;
            $this->statusMessage = "This widget hasn't been configured yet. Add an allowed domain in your Widget settings to activate it.";
            return;
        }

        // Domain Origin / Referer Validation
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
            // Allow direct navigation preview when valid embed_key is supplied
            $matched = true;
        }

        if (!$matched) {
            $this->isBlocked = true;
            $displayOrigin = $hostWithPort ?: 'Unknown Origin';
            $this->statusMessage = "Access Restricted: The domain ({$displayOrigin}) is not authorized to display this chat widget.";
            return;
        }

        // Check if human agents are online
        $onlineAgentsCount = TenantUser::where('is_active', true)
            ->where('status', 'online')
            ->where(function ($q) {
                $q->where('is_ai', false)->orWhereNull('is_ai');
            })
            ->count();

        if ($onlineAgentsCount === 0) {
            $this->isOffline = true;
        }
    }

    public function submit()
    {
        if ($this->isNotConfigured || $this->isBlocked) {
            return;
        }

        $this->validate();

        $visitor = Visitor::firstOrCreate(
            ['email' => $this->email],
            ['name' => $this->name, 'session_id' => Str::uuid()->toString(), 'ip_address' => request()->ip()]
        );

        $chat = Chat::create([
            'tenant_id' => tenant('id'),
            'visitor_id' => $visitor->id,
            'status' => 'open',
        ]);

        $bodyText = "Subject: " . $this->subject . "\n\n" . $this->description;

        $chat->messages()->create([
            'sender_id' => $visitor->id,
            'sender_type' => Visitor::class,
            'body' => $bodyText,
        ]);

        // Dispatch AI Auto-Reply Job if agents offline
        \App\Jobs\ProcessAiChatResponse::dispatch($chat->id, $bodyText);

        $this->reset(['name', 'email', 'subject', 'description']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.public.widget.ticket-form');
    }
}
