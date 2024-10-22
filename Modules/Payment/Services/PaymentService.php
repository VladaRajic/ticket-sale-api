<?php

namespace Modules\Payment\Services;

use App\Exceptions\ApiException;
use Illuminate\Database\DatabaseManager;
use Modules\Event\DTOs\EventDto;
use Modules\Payment\DTOs\TransactionDto;
use Modules\Payment\Events\TicketPurchased;
use Modules\Payment\Models\TicketPurchase;
use Modules\Payment\Repositories\IPaymentRepository;
use Modules\Payment\Validation\Validation;


class PaymentService implements IPaymentService
{
    public function __construct(
        protected IPaymentRepository $paymentRepository,
        protected DatabaseManager $databaseManager
    )
    {
    }

    /**
     * @throws ApiException
     * @throws \Throwable
     */
    public function purchaseTicket(EventDto $event, string $email, string $token): TransactionDto // return transaction ID
    {
        //1. Check Ticket availability
        Validation::checkIsEventExpired($event);

        //2. Checks for duplicate purchases using the same email
        Validation::checkIsTicketPurchased($email, $event);

        Validation::checkSeatsAvailability($event);
        //3 .Handles ticket purchases

        $ticketPurchase = $this->databaseManager->transaction(function () use($email, $token, $event) {
            $paymentInit = FakeStripePaymentSdk::make();
            $payment = $paymentInit->charge($email, $token);
            //4. Updates ticket availability after a successful transaction.
            $ticketPurchase = new TicketPurchase([
                'event_id' => $event->id,
                'email' => $email,
                'transaction_id' => $payment->transactionId
            ]);
            $this->paymentRepository->createPayment($ticketPurchase);

            return $ticketPurchase;
        });

        TicketPurchased::dispatch($ticketPurchase->event);

        return new TransactionDto($ticketPurchase['transaction_id']);
    }
}
