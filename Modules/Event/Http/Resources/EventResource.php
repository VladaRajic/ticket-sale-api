<?php

namespace Modules\Event\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Event\DTOs\EventDto;
use Modules\Event\Models\Event;

/** @mixin Event */
class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $event = EventDto::fromEloquentModel($this->getModel());
        return [
            'event_name' => $event->eventName,
            'available_tickets' => $event->availableTickets,
            'venue_name' => $event->venueName,
            'ticket_sales_end_date' => $event->ticketSalesEndDate,
        ];
    }
}
