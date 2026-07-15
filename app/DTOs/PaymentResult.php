<?php

declare(strict_types=1);

namespace App\DTOs;

class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly string $transactionId,
        public readonly ?string $errorMessage = null,
    ) {
    }

    public function successful(): bool
    {
        return $this->success;
    }
}
