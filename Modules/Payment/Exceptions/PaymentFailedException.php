<?php

namespace Modules\Payment\Exceptions;

use App\Exceptions\ApiException;

class PaymentFailedException extends ApiException
{

    const ERR_PAYMENT_FAILED = 5;
    public function __construct($message = "", $code = 0, int $apiCode = 0)
    {
        parent::__construct($message, $code, $apiCode);
    }
}
