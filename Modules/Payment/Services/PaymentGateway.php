<?php

namespace Modules\Payment\Services;

interface PaymentGateway
{
    public function charge(string $email, string $token): SuccessfulPayment;
}
