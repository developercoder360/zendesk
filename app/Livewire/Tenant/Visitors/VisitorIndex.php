<?php

namespace App\Livewire\Tenant\Visitors;

use App\Models\Visitor;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

#[Layout('layouts.tenant')]
class VisitorIndex extends Component
{
    use WithPagination;

    public $search = '';
    
    public $banModalOpen = false;
    public $visitorToBanId = null;
    public $banReason = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openBanModal($visitorId)
    {
        $this->visitorToBanId = $visitorId;
        $this->banReason = '';
        $this->banModalOpen = true;
    }

    public function banVisitor()
    {
        if ($this->visitorToBanId) {
            $visitor = Visitor::find($this->visitorToBanId);
            if ($visitor) {
                $visitor->update([
                    'is_banned' => true,
                    'banned_at' => Carbon::now(),
                    'ban_reason' => $this->banReason,
                ]);
            }
        }
        
        $this->banModalOpen = false;
        $this->visitorToBanId = null;
        $this->banReason = '';
    }

    public function unbanVisitor($visitorId)
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
        $query = Visitor::with(['servedByAgent.user', 'chats']);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhere('current_page_title', 'like', '%' . $this->search . '%')
                  ->orWhere('referrer', 'like', '%' . $this->search . '%');
            });
        }
        
        return view('livewire.tenant.visitors.visitor-index', [
            'visitors' => $query->latest('last_seen_at')->paginate(10)
        ]);
    }
}
