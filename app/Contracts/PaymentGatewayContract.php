<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\PaymentIntent;
use App\DTOs\PaymentResult;

interface PaymentGatewayContract
{
    public function charge(PaymentIntent $intent): PaymentResult;
}
