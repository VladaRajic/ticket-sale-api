<?php

namespace Modules\Payment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Event\Listeners\UpdateNumberOfAvailableTickets;
use Modules\Payment\Events\TicketPurchased;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        TicketPurchased::class => [
            UpdateNumberOfAvailableTickets::class,
        ],
    ];
}
