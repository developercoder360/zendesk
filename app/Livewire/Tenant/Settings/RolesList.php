<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class RolesList extends Component
{
    public function with(): array
    {
        return [
            'roles' => Role::withCount('users')->where('tenant_id', tenant('id'))->get(),
        ];
    }

    public function deleteRole(Role $role)
    {
        if ($role->name === 'Owner' || $role->name === 'Company Admin') {
            $this->addError('error', 'Cannot delete system roles.');
            return;
        }
        
        $role->delete();
    }

    public function render()
    {
        return view('livewire.tenant.settings.roles-list');
    }
}
