<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TenantUserController extends Controller
{
    /**
     * Store a newly created tenant user/agent in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'   => ['required', Rules\Password::defaults()],
            'role'       => ['required', 'in:manager,agent'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'shift'      => ['nullable', 'string', 'max:255'],
        ]);

        $tenantId = tenant('id');

        $user = DB::transaction(function () use ($validated, $tenantId) {
            
            // 1. Create the central Authentication record
            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role'      => $validated['role'],
                'tenant_id' => $tenantId,
            ]);

            // 2. Create the RLS-protected Tenant Profile
            TenantUser::create([
                'user_id'       => $user->id,
                'tenant_id'     => $tenantId,
                'department_id' => $validated['department_id'] ?? null,
                'shift'         => $validated['shift'] ?? null,
                'status'        => 'offline',
            ]);

            return $user;
        });

        // Redirect back with success message (or wherever the index page is)
        return redirect()->back()->with('status', 'Employee successfully added.');
    }
}
