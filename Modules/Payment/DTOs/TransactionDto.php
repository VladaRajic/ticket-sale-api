<?php

namespace Modules\Payment\DTOs;

class TransactionDto
{
    public function __construct(public string $transactionId)
    {
    }
}
