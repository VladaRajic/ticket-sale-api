<?php

namespace Modules\Event\Tests\Feature;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Event\Models\Event;
use Modules\Payment\Models\TicketPurchase;
use Modules\Venue\Database\factories\VenueFactory;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_available_seats()
    {
        $venue = VenueFactory::new()->create(
            new Sequence(
                ['name' => 'Dvorana 1', 'capacity' => 150,]
            )
        );

        $event = new Event([
            'venue_id' => $venue->id,
            'name' => 'Event 1',
            'available_tickets' => 150,
            'ticket_sales_end_date' => '2024-05-01 10:00:00'
        ]);
        $event->save();

        TicketPurchase::create(['event_id' => $event->id, 'email' => 'test@test.com', 'transaction_id' => 'fsdfssdf']);

        $this->assertSame(149, $event->updateAvailableSeats());
    }
}
