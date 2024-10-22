<?php

namespace Modules\Event\Tests\Feature\Http\Controller;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Modules\Event\database\factories\EventFactory;
use Modules\Event\Models\Event as EventModel;
use Modules\Payment\Events\TicketPurchased;
use Modules\Payment\Exceptions\PaymentExceptions;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\Models\TicketPurchase;
use Modules\Payment\Services\FakeStripePaymentSdk;
use Modules\Venue\database\factories\VenueFactory;
use Tests\TestCase;
use Event;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_all_events()
    {
        $endDate = now()->addDays(15)->toDateTimeString();
        $venue = VenueFactory::new()->create(
            new Sequence(
                ['name' => 'Dvorana 1', 'capacity' => 150,]
            )
        );
        $event = EventFactory::new()->create(
            new Sequence(
                [
                    'venue_id' => $venue->id,
                    'name' => 'Koncert 1',
                    'available_tickets' => 150,
                    'ticket_sales_end_date' => $endDate,
                ]
            )
        );

        $response = $this->get('/api/events');
        $response->assertJson(
            fn(AssertableJson $json) =>
            $json->has(1)
                ->first(fn(AssertableJson $json) =>
                    $json
                    ->where('event_name', $event->name)
                    ->where('available_tickets', $event->available_tickets)
                    ->where('venue_name', $event->venue->name)
                    ->where('ticket_sales_end_date', $event->ticket_sales_end_date)
                    ->etc()
                )
        );
        $response->assertStatus(200);
    }

    public function test_purchase_ticket_successfully()
    {
        Event::fake();
        $user = UserFactory::new()->create();
        $endDate = now()->addDays(15)->toDateTimeString();
        $venue = VenueFactory::new()->create(
            new Sequence(
                ['name' => 'Dvorana 1', 'capacity' => 150,]
            )
        );
        $event = EventFactory::new()->create(
            new Sequence(
                [
                    'venue_id' => $venue->id,
                    'name' => 'Koncert 1',
                    'available_tickets' => 150,
                    'ticket_sales_end_date' => $endDate,
                ]
            )
        );

        $response = $this->post("/api/events/$event->id/purchase", [
            'email' => $user->email,
            'token' => FakeStripePaymentSdk::validToken()
        ]);
        Event::assertDispatched(TicketPurchased::class);
        $lastTransaction = TicketPurchase::get()->last();

        $this->assertSame($response['transaction_id'], $lastTransaction->transaction_id);
        $this->assertSame($user->email, $lastTransaction->email);
        $response->assertStatus(200);

    }

    public function test_purchase_duplicate_mails()
    {
        $user = UserFactory::new()->create();
        $endDate = now()->addDays(15)->toDateTimeString();
        $venue = VenueFactory::new()->create(
            new Sequence(
                ['name' => 'Dvorana 1', 'capacity' => 150,]
            )
        );
        $event = EventFactory::new()->create(
            new Sequence(
                [
                    'venue_id' => $venue->id,
                    'name' => 'Koncert 1',
                    'available_tickets' => 150,
                    'ticket_sales_end_date' => $endDate,
                ]
            )
        );

        TicketPurchase::create([
            'event_id' => $event->id,
            'email' => $user->email,
            'transaction_id' => 'fsdsfsgfd'
        ]);

        $this->expectExceptionObject(PaymentExceptions::eventIsPurchased($event->name));
        $this->withoutExceptionHandling()->post("/api/events/$event->id/purchase", [
            'email' => $user->email,
            'token' => FakeStripePaymentSdk::validToken()
        ]);
    }

    public function test_soldout_all_tickets()
    {
        $user = UserFactory::new()->create();
        $endDate = now()->addDays(15)->toDateTimeString();
        $venue = VenueFactory::new()->create(
            new Sequence(
                ['name' => 'Dvorana 1', 'capacity' => 150,]
            )
        );
        $event = EventFactory::new()->create(
            new Sequence(
                [
                    'venue_id' => $venue->id,
                    'name' => 'Koncert 1',
                    'available_tickets' => 0,
                    'ticket_sales_end_date' => $endDate,
                ]
            )
        );

        TicketPurchase::create([
            'event_id' => $event->id,
            'email' => 'some@email.com',
            'transaction_id' => 'fsdsfsgfd'
        ]);

        $this->expectExceptionObject(PaymentExceptions::seatsAreNotAvailable($event->name));
        $this->withoutExceptionHandling()->post("/api/events/$event->id/purchase", [
            'email' => $user->email,
            'token' => FakeStripePaymentSdk::validToken()
        ]);
    }

    public function test_event_has_expired()
    {
        $user = UserFactory::new()->create();
        $endDate = now()->subDay()->toDateTimeString();
        $venue = VenueFactory::new()->create(
            new Sequence(
                ['name' => 'Dvorana 1', 'capacity' => 150,]
            )
        );
        $event = EventFactory::new()->create(
            new Sequence(
                [
                    'venue_id' => $venue->id,
                    'name' => 'Koncert 1',
                    'available_tickets' => 0,
                    'ticket_sales_end_date' => $endDate,
                ]
            )
        );

        TicketPurchase::create([
            'event_id' => $event->id,
            'email' => 'some@email.com',
            'transaction_id' => 'fsdsfsgfd'
        ]);

        $this->expectExceptionObject(PaymentExceptions::eventIsExpired($event->name));
        $this->withoutExceptionHandling()->post("/api/events/$event->id/purchase", [
            'email' => $user->email,
            'token' => FakeStripePaymentSdk::validToken()
        ]);
    }

    public function test_transaction_not_pass()
    {
        $user = UserFactory::new()->create();
        $endDate = now()->addDays(15)->toDateTimeString();
        $venue = VenueFactory::new()->create(
            new Sequence(
                ['name' => 'Dvorana 1', 'capacity' => 150,]
            )
        );
        $event = EventFactory::new()->create(
            new Sequence(
                [
                    'venue_id' => $venue->id,
                    'name' => 'Koncert 1',
                    'available_tickets' => 150,
                    'ticket_sales_end_date' => $endDate,
                ]
            )
        );

        TicketPurchase::create([
            'event_id' => $event->id,
            'email' => 'some@email.com',
            'transaction_id' => 'fsdsfsgfd'
        ]);

        $this->expectException(PaymentFailedException::class);
        $this->withoutExceptionHandling()->post("/api/events/$event->id/purchase", [
            'email' => $user->email,
            'token' => FakeStripePaymentSdk::invalidToken()
        ]);
    }

}
