<?php

namespace Modules\Payment\Services;

use App\Exceptions\ErrorResponse;
use Modules\Payment\Exceptions\PaymentFailedException;

class FakeStripePaymentSdk implements PaymentGateway
{
    /**
     * @throws PaymentFailedException
     */
    public function charge(string $email, string $token): SuccessfulPayment
    {
        $this->validateToken($token);

        return new SuccessfulPayment(\Str::uuid(), $email);
    }

    public static function make(): FakeStripePaymentSdk
    {
        return new self();
    }

    public static function validToken(): string
    {
        return (string) \Str::uuid();
    }

    public static function invalidToken(): string
    {
        return substr(self::validToken(), -35);
    }

    /**
     * @throws PaymentFailedException
     */
    protected function validateToken(string $token): void
    {
        if (! \Str::isUuid($token)) {
            throw new PaymentFailedException(
                'The given payment token is not valid.',
                ErrorResponse::BAD_REQUEST,
                PaymentFailedException::ERR_PAYMENT_FAILED
            );
        }
    }
}
