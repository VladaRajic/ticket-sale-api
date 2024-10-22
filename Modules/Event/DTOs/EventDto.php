<?php

namespace Modules\Event\DTOs;

use Modules\Event\Models\Event;

class EventDto
{
    public function __construct(
        public int $id,
        public string $eventName,
        public int $availableTickets,
        public string $venueName,
        public string $ticketSalesEndDate
    )
    {
    }

    public static function fromEloquentModel(Event $event): EventDto
    {
        return new EventDto(
            id: $event->id,
            eventName: $event->name,
            availableTickets: $event->available_tickets,
            venueName: $event->venue->name,
            ticketSalesEndDate: $event->ticket_sales_end_date
        );
    }
}
