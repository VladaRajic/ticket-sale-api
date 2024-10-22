<?php

namespace Modules\Payment\Validation;

use App\Exceptions\ApiException;
use Carbon\Carbon;
use Modules\Event\DTOs\EventDto;
use Modules\Payment\Exceptions\PaymentExceptions;
use Modules\Payment\Models\TicketPurchase;

class Validation
{
    /**
     * @throws ApiException
     */
    public static function checkIsEventExpired(EventDto $event): void
    {
        $checkTicketAvailability = Carbon::createFromFormat('Y-m-d H:i:s', $event->ticketSalesEndDate)->lessThan(Carbon::now());
        if($checkTicketAvailability){
            throw PaymentExceptions::eventIsExpired($event->eventName);
        }
    }

    /**
     * @throws ApiException
     */
    public static function checkIsTicketPurchased(string $email, EventDto $event): void
    {
        $isTicketPurchased = TicketPurchase::where('email', $email)->exists();
        if($isTicketPurchased){
            throw PaymentExceptions::eventIsPurchased($event->eventName);
        }
    }

    /**
     * @throws ApiException
     */
    public static function checkSeatsAvailability(EventDto $event): void
    {
        $status = $event->availableTickets > 0;
        if(!$status){
            throw PaymentExceptions::seatsAreNotAvailable($event->eventName);
        }
    }
}
