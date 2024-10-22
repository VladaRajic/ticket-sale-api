<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Event\Http\Requests\TicketPurchaseRequest;
use Modules\Event\Http\Resources\EventResource;
use Modules\Event\Services\IEventService;
use Modules\Payment\Services\FakeStripePaymentSdk;
use Modules\Payment\Services\IPaymentService;


class EventController extends Controller
{
    public function __construct(protected IEventService $eventService, protected IPaymentService $paymentService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json(EventResource::collection($this->eventService->getEvents()));
    }

    public function purchase(int $eventId, TicketPurchaseRequest $request): JsonResponse
    {
        $email =  $request->getEmail();
        $event = $this->eventService->getEvent($eventId);
        $token = $request->getToken();

        $transaction = $this->paymentService->purchaseTicket($event, $email, $token); // simulate valid token

        return response()->json(['transaction_id' => $transaction->transactionId]);
    }
}
