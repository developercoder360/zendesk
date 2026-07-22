<?php

namespace App\Livewire\Tenant\Settings\Banned;

use App\Models\Visitor;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class BannedIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $isModalOpen = false;
    public $ip_address = '';
    public $ban_reason = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openAddBanModal()
    {
        $this->resetValidation();
        $this->ip_address = '';
        $this->ban_reason = '';
        $this->isModalOpen = true;
    }

    public function saveBan()
    {
        $this->validate([
            'ip_address' => 'required|string|max:45',
            'ban_reason' => 'nullable|string|max:255',
        ]);

        $visitor = Visitor::firstOrCreate(
            ['ip_address' => $this->ip_address],
            ['name' => 'Banned IP ' . $this->ip_address]
        );

        $visitor->update([
            'is_banned' => true,
            'banned_at' => Carbon::now(),
            'ban_reason' => $this->ban_reason,
        ]);

        $this->isModalOpen = false;
    }

    public function unban($visitorId)
    {
        $visitor = Visitor::find($visitorId);
        if ($visitor) {
            $visitor->update([
                'is_banned' => false,
                'banned_at' => null,
                'ban_reason' => null,
            ]);
        }
    }

    public function render()
    {
        $query = Visitor::where('is_banned', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('ban_reason', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.tenant.settings.banned.banned-index', [
            'bannedVisitors' => $query->latest('banned_at')->paginate(10),
        ]);
    }
}
