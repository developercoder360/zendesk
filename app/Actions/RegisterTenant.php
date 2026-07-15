<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\RegistrationDTO;
use App\Events\TenantRegistered;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterTenant
{
    public function execute(RegistrationDTO $dto): Tenant
    {
        return DB::transaction(function () use ($dto) {
            $tenant = Tenant::create([
                'company_name' => $dto->companyName,
                'phone' => $dto->phone,
                'country' => $dto->country,
                'timezone' => $dto->timezone,
                'plan_id' => $dto->planId,
            ]);

            $centralDomain = config('tenancy.central_domains')[0] ?? 'zendesk.test';

            Domain::create([
                'domain' => $dto->companySlug . '.' . $centralDomain,
                'tenant_id' => $tenant->id,
            ]);

            $owner = User::create([
                'name' => $dto->ownerName,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'tenant_id' => $tenant->id,
            ]);

            TenantRegistered::dispatch($tenant, $owner);

            return $tenant;
        });
    }
}
