<?php

namespace App\Livewire\Tenant\Agents;

use App\Livewire\Forms\TenantUserForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
#[Title('Agents')]
class AgentIndex extends Component
{
    use WithPagination;

    public TenantUserForm $form;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    public ?User $userToDelete = null;
    public ?\App\Models\TenantUser $tenantUserToDelete = null;
    public string $search = '';
    public string $roleFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->form->reset();
        $this->showCreateModal = true;
    }

    public function openEditModal(User $user)
    {
        $this->form->reset();
        $this->form->setUser($user);
        $this->showEditModal = true;
    }

    public function openDeleteModal(User $user)
    {
        $this->userToDelete = $user;
        $this->tenantUserToDelete = \App\Models\TenantUser::where('user_id', $user->id)
            ->withCount(['assignedChats' => fn($q) => $q->where('status', 'open')])
            ->first();
        $this->showDeleteModal = true;
    }

    public function save()
    {
        $this->form->store();
        $this->showCreateModal = false;
        
        $this->dispatch('toast', message: 'Agent successfully created.', type: 'success');
    }

    public function update()
    {
        $this->form->update();
        $this->showEditModal = false;

        $this->dispatch('toast', message: 'Agent successfully updated.', type: 'success');
    }

    public function delete()
    {
        if (!$this->userToDelete) {
            $this->showDeleteModal = false;
            return;
        }

        // 1. Prevent backend self-deletion
        if ($this->userToDelete->id === auth()->id()) {
            $this->dispatch('toast', message: "You can't delete your own account.", type: 'error');
            $this->showDeleteModal = false;
            $this->userToDelete = null;
            $this->tenantUserToDelete = null;
            return;
        }

        // 2. Prevent deleting the last remaining Owner
        setPermissionsTeamId(tenant('id'));
        if ($this->userToDelete->hasRole('Owner')) {
            $ownerCount = User::where('tenant_id', tenant('id'))
                ->whereHas('roles', fn($q) => $q->where('name', 'Owner'))
                ->count();

            if ($ownerCount <= 1) {
                $this->dispatch('toast', message: "Every workspace needs at least one Owner — assign another Owner before removing this one.", type: 'error');
                $this->showDeleteModal = false;
                $this->userToDelete = null;
                $this->tenantUserToDelete = null;
                return;
            }
        }

        // Proceed with deletion if tenant matches
        if ($this->userToDelete->tenant_id === tenant('id')) {
            $this->userToDelete->delete();
            $this->dispatch('toast', message: 'Agent successfully deleted.', type: 'success');
        }

        $this->userToDelete = null;
        $this->tenantUserToDelete = null;
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $query = \App\Models\TenantUser::where(function ($q) {
                $q->where('is_ai', false)->orWhereNull('is_ai');
            })
            ->with(['user.roles', 'department'])
            ->withCount(['assignedChats' => function ($q) {
                $q->where('status', 'open');
            }]);

        if (!empty($this->search)) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('email', 'ilike', '%' . $this->search . '%');
            });
        }

        if (!empty($this->roleFilter)) {
            $query->whereHas('user.roles', function($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->latest()->paginate(10);
        $departments = \App\Models\Department::all();

        return view('livewire.tenant.agents.agent-index', [
            'users' => $users,
            'departments' => $departments,
        ]);
    }
}
