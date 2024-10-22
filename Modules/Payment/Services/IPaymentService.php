<?php

namespace Modules\Payment\Services;

use Modules\Event\DTOs\EventDto;
use Modules\Payment\DTOs\TransactionDto;


interface IPaymentService
{
    public function purchaseTicket(EventDto $event, string $email, string $token): TransactionDto;
}
