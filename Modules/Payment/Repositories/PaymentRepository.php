<?php

namespace Modules\Payment\Repositories;


use Modules\Payment\Models\TicketPurchase;

class PaymentRepository implements IPaymentRepository
{

    public function createPayment(TicketPurchase $ticketPurchase): TicketPurchase
    {
        $ticketPurchase->save();
        return $ticketPurchase;
    }
}
