<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGatewayContract;
use App\DTOs\PaymentIntent;
use App\DTOs\PaymentResult;
use Illuminate\Support\Str;

class MockPaymentGateway implements PaymentGatewayContract
{
    public function charge(PaymentIntent $intent): PaymentResult
    {
        // Mock a failure if a specific fake card is used (e.g. for testing)
        if ($intent->paymentMethodId === 'tok_fail') {
            return new PaymentResult(
                success: false,
                transactionId: '',
                errorMessage: 'Your card was declined.'
            );
        }

        // Mock a successful payment
        return new PaymentResult(
            success: true,
            transactionId: 'mock_txn_'.Str::random(10)
        );
    }
}
