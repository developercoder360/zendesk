<?php

declare(strict_types=1);

namespace App\DTOs;

class PaymentIntent
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $paymentMethodId,
        public readonly string $email,
    ) {}
}
