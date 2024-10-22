<?php

namespace Modules\Event\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Event\Models\Event;
use Modules\Event\Repositories\IEventRepository;
use Modules\Payment\Events\TicketPurchased;
use Modules\Payment\Repositories\IPaymentRepository;

class UpdateNumberOfAvailableTickets
{
    /**
     * Create the event listener.
     */
    public function __construct(protected IEventRepository $eventRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketPurchased $event): void
    {
        $event->event->updateAvailableSeats();;
    }
}
