<?php

namespace App\Livewire\Forms;

use App\Models\User;
use App\Models\TenantUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TenantUserForm extends Form
{
    public ?User $user = null;
    public ?TenantUser $tenantUser = null;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|email|max:255|unique:users,email')]
    public $email = '';

    #[Validate('nullable|string|min:8')]
    public $password = '';

    #[Validate('required|in:agent,manager,owner')]
    public $role = 'agent';

    #[Validate('nullable|exists:departments,id')]
    public $department_id = null;

    #[Validate('nullable|string|max:255')]
    public $position = '';

    #[Validate('nullable|string|max:255')]
    public $phone = '';

    #[Validate('boolean')]
    public $is_active = true;

    public function setUser(?User $user)
    {
        $this->user = $user;
        
        if ($user) {
            $this->tenantUser = $user->tenantProfile;
            
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            
            if ($this->tenantUser) {
                $this->department_id = $this->tenantUser->department_id;
                $this->position = $this->tenantUser->position;
                $this->phone = $this->tenantUser->phone;
                $this->is_active = $this->tenantUser->is_active;
            }
        }
    }

    public function store()
    {
        $this->validate();

        $tenantId = tenant('id');

        DB::transaction(function () use ($tenantId) {
            $user = User::create([
                'name'      => $this->name,
                'email'     => $this->email,
                'password'  => Hash::make($this->password),
                'role'      => $this->role,
                'tenant_id' => $tenantId,
            ]);

            TenantUser::create([
                'user_id'       => $user->id,
                'tenant_id'     => $tenantId,
                'department_id' => $this->department_id,
                'position'      => $this->position,
                'phone'         => $this->phone,
                'is_active'     => $this->is_active,
                'status'        => 'offline',
            ]);
        });

        $this->reset();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'role' => 'required|in:agent,manager,owner',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () {
            $this->user->update([
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $this->role,
            ]);

            if ($this->password) {
                $this->user->update(['password' => Hash::make($this->password)]);
            }

            if ($this->tenantUser) {
                $this->tenantUser->update([
                    'department_id' => $this->department_id,
                    'position'      => $this->position,
                    'phone'         => $this->phone,
                    'is_active'     => $this->is_active,
                ]);
            }
        });

        $this->reset();
    }
}
