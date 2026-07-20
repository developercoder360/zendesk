<?php

declare(strict_types=1);

namespace App\DTOs;

class RegistrationDTO
{
    public function __construct(
        public readonly string $ownerName,
        public readonly string $companyName,
        public readonly string $companySlug,
        public readonly string $email,
        public readonly string $password,
        public readonly string $phone,
        public readonly string $country,
        public readonly string $timezone,
        public readonly int $packageId,
    ) {}
}
