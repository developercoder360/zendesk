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

    #[Validate('required|in:Agent,Company Admin,Owner,Viewer')]
    public $role = 'Agent';

    #[Validate('nullable|exists:departments,id')]
    public $department_id = null;

    #[Validate('nullable|string|max:255')]
    public $position = '';

    #[Validate('nullable|string|max:255')]
    public $phone = '';

    #[Validate('nullable|in:online,offline,away')]
    public $status = 'offline';

    #[Validate('nullable|string|max:255')]
    public $shift = '';

    #[Validate('nullable|string|max:500')]
    public $avatar = '';

    #[Validate('boolean')]
    public $is_active = true;

    public function setUser(?User $user)
    {
        $this->user = $user;
        
        if ($user) {
            $this->tenantUser = $user->tenantProfile;
            
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->roles->first()?->name ?? ucfirst($user->role ?? 'Agent');
            
            if ($this->tenantUser) {
                $this->department_id = $this->tenantUser->department_id;
                $this->position = $this->tenantUser->position;
                $this->phone = $this->tenantUser->phone;
                $this->status = $this->tenantUser->status ?? 'offline';
                $this->shift = $this->tenantUser->shift ?? '';
                $this->avatar = $this->tenantUser->avatar ?? '';
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
                'role'      => strtolower($this->role),
                'tenant_id' => $tenantId,
            ]);

            $user->assignRole($this->role);

            TenantUser::create([
                'user_id'       => $user->id,
                'tenant_id'     => $tenantId,
                'department_id' => $this->department_id,
                'position'      => $this->position,
                'phone'         => $this->phone,
                'status'        => $this->status ?: 'offline',
                'shift'         => $this->shift,
                'avatar'        => $this->avatar,
                'is_active'     => $this->is_active,
                'joined_at'     => now(),
            ]);
        });

        $this->reset();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'role' => 'required|in:Agent,Company Admin,Owner,Viewer',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'status' => 'nullable|in:online,offline,away',
            'shift' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () {
            $this->user->update([
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => strtolower($this->role),
            ]);

            $this->user->syncRoles([$this->role]);

            if ($this->password) {
                $this->user->update(['password' => Hash::make($this->password)]);
            }

            if ($this->tenantUser) {
                $this->tenantUser->update([
                    'department_id' => $this->department_id,
                    'position'      => $this->position,
                    'phone'         => $this->phone,
                    'status'        => $this->status ?: 'offline',
                    'shift'         => $this->shift,
                    'avatar'        => $this->avatar,
                    'is_active'     => $this->is_active,
                ]);
            }
        });

        $this->reset();
    }
}
