<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class RoleForm extends Component
{
    public ?Role $role = null;
    public string $name = '';
    public array $selectedPermissions = [];

    public function mount(?Role $role = null)
    {
        if ($role && $role->exists) {
            $this->role = $role;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($this->role && in_array($this->role->name, ['Owner', 'Company Admin'])) {
            // Can't rename system roles
            $this->name = $this->role->name;
        }

        if (!$this->role) {
            $this->role = Role::create([
                'name' => $this->name,
                'tenant_id' => tenant('id')
            ]);
        } else {
            $this->role->update(['name' => $this->name]);
        }

        $this->role->syncPermissions($this->selectedPermissions);

        return $this->redirect(route('tenant.settings.roles.index'), navigate: true);
    }

    public function with(): array
    {
        // Group permissions dynamically or manually. Here we do it manually by prefix for a cleaner UI.
        $allPermissions = Permission::all()->pluck('name')->toArray();
        $grouped = [];
        
        foreach ($allPermissions as $perm) {
            $parts = explode('_', $perm, 2);
            $module = count($parts) > 1 ? ucfirst($parts[1]) : 'General';
            $grouped[$module][] = $perm;
        }

        return [
            'permissionGroups' => $grouped,
        ];
    }

    public function render()
    {
        return view('livewire.tenant.settings.role-form');
    }
}
