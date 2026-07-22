<?php

namespace App\Livewire\Tenant\Settings\Shortcuts;

use App\Models\CannedResponse;
use App\Models\TenantUser;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class ShortcutIndex extends Component
{
    use WithPagination;

    // --- Filter / Sort / Search ---
    public string $search = '';
    public string $sortField = 'shortcut_key';
    public string $sortDirection = 'asc';
    public string $filterAgent = 'all'; // 'all' | 'global' | numeric agent ID

    // --- Bulk Selection ---
    public array $selectedIds = [];
    public bool $selectAll = false;

    // --- Modal State ---
    public bool $isModalOpen = false;
    public ?int $shortcutId = null;
    public string $title = '';
    public string $shortcut = '';
    public string $content = '';
    public bool $isShared = false;

    protected $rules = [
        'title'    => 'required|string|max:255',
        'shortcut' => 'required|string|max:50',
        'content'  => 'required|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function updatingFilterAgent()
    {
        $this->resetPage();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->shortcutId = null;
        $this->title      = '';
        $this->shortcut   = '';
        $this->content    = '';
        $this->isShared   = false;
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $row = CannedResponse::findOrFail($id);
        $this->shortcutId  = $row->id;
        $this->title       = $row->title;
        $this->shortcut    = $row->shortcut_key;
        $this->content     = $row->body;
        $this->isShared    = is_null($row->tenant_user_id);
        $this->isModalOpen = true;
    }

    public function save(): void
    {
        $this->validate();

        $tenantUserId = $this->isShared
            ? null
            : auth()->user()->tenantProfile?->id;

        $data = [
            'title'          => $this->title,
            'shortcut_key'   => $this->shortcut,
            'body'           => $this->content,
            'tenant_user_id' => $tenantUserId,
        ];

        if ($this->shortcutId) {
            CannedResponse::findOrFail($this->shortcutId)->update($data);
        } else {
            CannedResponse::create($data);
        }

        $this->isModalOpen  = false;
        $this->selectedIds  = [];
        $this->selectAll    = false;
    }

    public function delete(int $id): void
    {
        CannedResponse::findOrFail($id)->delete();
        $this->selectedIds = array_filter($this->selectedIds, fn($i) => $i !== $id);
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedIds)) {
            return;
        }
        CannedResponse::whereIn('id', $this->selectedIds)->delete();
        $this->selectedIds = [];
        $this->selectAll   = false;
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = CannedResponse::pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function render()
    {
        $query = CannedResponse::with('tenantUser.user');

        // Search
        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(title) LIKE LOWER(?)', [$term])
                  ->orWhereRaw('LOWER(shortcut_key) LIKE LOWER(?)', [$term])
                  ->orWhereRaw('LOWER(body) LIKE LOWER(?)', [$term]);
            });
        }

        // Filter
        if ($this->filterAgent === 'global') {
            $query->whereNull('tenant_user_id');
        } elseif ($this->filterAgent !== 'all' && is_numeric($this->filterAgent)) {
            $query->where('tenant_user_id', $this->filterAgent);
        }

        // Sort
        $query->orderBy($this->sortField, $this->sortDirection);

        $total     = $query->count();
        $shortcuts = $query->paginate(15);

        // Agents for filter dropdown
        $agents = TenantUser::with('user')->get();

        return view('livewire.tenant.settings.shortcuts.shortcut-index', [
            'shortcuts' => $shortcuts,
            'total'     => $total,
            'agents'    => $agents,
        ]);
    }
}
