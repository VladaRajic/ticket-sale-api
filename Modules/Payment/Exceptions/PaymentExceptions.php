<?php

namespace Modules\Payment\Exceptions;

use App\Exceptions\ApiException;
use App\Exceptions\ErrorResponse;

class PaymentExceptions
{
    const ERR_EVENT_EXPIRED = 1;
    const ERR_EVENT_IS_PURCHASED = 2;
    const ERR_SEATS_NO_AVAILABLE = 3;
    public static function eventIsExpired(string $eventName): ApiException
    {
        return new ApiException(
            "Event $eventName are not available anymore.",
            ErrorResponse::BAD_REQUEST,
            self::ERR_EVENT_EXPIRED,
        );
    }

    public static function eventIsPurchased(string $eventName): ApiException
    {
        return new ApiException(
            "Event $eventName is already purchased.",
            ErrorResponse::BAD_REQUEST,
            self::ERR_EVENT_IS_PURCHASED,
        );
    }

    public static function seatsAreNotAvailable(string $eventName): ApiException
    {
        return new ApiException(
            "Seats for event $eventName are not available.",
            ErrorResponse::BAD_REQUEST,
            self::ERR_SEATS_NO_AVAILABLE,
        );
    }
}
