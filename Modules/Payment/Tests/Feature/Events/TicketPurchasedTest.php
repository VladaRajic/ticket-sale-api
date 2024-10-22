<?php

namespace Modules\Payment\Tests\Feature\Events;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Event;
use Modules\Event\Listeners\UpdateNumberOfAvailableTickets;
use Modules\Payment\Events\TicketPurchased;
use Tests\TestCase;


class TicketPurchasedTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_listeners()
    {
        Event::fake();
        Event::assertListening(TicketPurchased::class, UpdateNumberOfAvailableTickets::class);
    }

}
