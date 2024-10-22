<?php

namespace Modules\Payment\Services;

class SuccessfulPayment
{
    public function __construct(public string $transactionId, public string $email)
    {
    }
}
