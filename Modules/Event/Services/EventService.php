<?php

namespace Modules\Event\Services;

use Illuminate\Support\Collection;
use Modules\Event\DTOs\EventDto;
use Modules\Event\Repositories\IEventRepository;

class EventService implements IEventService
{
    public function __construct(protected IEventRepository $eventRepository)
    {
    }

    public function getEvent(int $eventId): EventDto
    {
        return $this->eventRepository->getEvent($eventId);
    }

    public function getEvents(): Collection
    {
        return $this->eventRepository->getEvents();
    }

}
