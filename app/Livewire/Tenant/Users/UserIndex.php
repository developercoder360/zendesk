<?php

namespace App\Livewire\Tenant\Users;

use App\Livewire\Forms\TenantUserForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
#[Title('User Management')]
class UserIndex extends Component
{
    use WithPagination;

    public TenantUserForm $form;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    public ?User $userToDelete = null;
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
        $this->showDeleteModal = true;
    }

    public function save()
    {
        $this->form->store();
        $this->showCreateModal = false;
        
        $this->dispatch('toast', message: 'User successfully created.', type: 'success');
    }

    public function update()
    {
        $this->form->update();
        $this->showEditModal = false;

        $this->dispatch('toast', message: 'User successfully updated.', type: 'success');
    }

    public function delete()
    {
        if ($this->userToDelete) {
            // Also delete the tenant profile implicitly via Cascade, 
            // but let's make sure it's the current tenant's user
            if ($this->userToDelete->tenant_id === tenant('id')) {
                $this->userToDelete->delete();
                $this->dispatch('toast', message: 'User successfully deleted.', type: 'success');
            }
            $this->userToDelete = null;
        }
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $query = User::with('tenantProfile')
            ->where('tenant_id', tenant('id'));

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('email', 'ilike', '%' . $this->search . '%');
            });
        }

        if (!empty($this->roleFilter)) {
            $query->where('role', $this->roleFilter);
        }

        $users = $query->orderBy('name')->paginate(10);

        return view('livewire.tenant.users.user-index', [
            'users' => $users
        ]);
    }
}
