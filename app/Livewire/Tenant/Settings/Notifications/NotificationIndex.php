<?php

namespace App\Livewire\Tenant\Settings\Notifications;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class NotificationIndex extends Component
{
    public bool $email_new_ticket = true;
    public bool $email_assignment = true;
    public bool $email_mention = true;
    public bool $sound_alerts = true;
    public bool $desktop_push = false;

    public bool $saved = false;

    public function mount()
    {
        $profile = auth()->user()->tenantProfile;
        $prefs = $profile->notification_preferences ?? [];

        $this->email_new_ticket = $prefs['email_new_ticket'] ?? true;
        $this->email_assignment = $prefs['email_assignment'] ?? true;
        $this->email_mention = $prefs['email_mention'] ?? true;
        $this->sound_alerts = $prefs['sound_alerts'] ?? true;
        $this->desktop_push = $prefs['desktop_push'] ?? false;
    }

    public function save()
    {
        $profile = auth()->user()->tenantProfile;

        if ($profile) {
            $profile->update([
                'notification_preferences' => [
                    'email_new_ticket' => $this->email_new_ticket,
                    'email_assignment' => $this->email_assignment,
                    'email_mention' => $this->email_mention,
                    'sound_alerts' => $this->sound_alerts,
                    'desktop_push' => $this->desktop_push,
                ],
            ]);
        }

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.tenant.settings.notifications.notification-index');
    }
}
