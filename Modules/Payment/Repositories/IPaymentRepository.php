<?php

namespace Modules\Payment\Repositories;

use Modules\Payment\Models\TicketPurchase;

interface IPaymentRepository
{
    public function createPayment(TicketPurchase $ticketPurchase): TicketPurchase;
}
