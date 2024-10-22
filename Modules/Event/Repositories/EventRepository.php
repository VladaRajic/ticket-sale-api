<?php

namespace Modules\Event\Repositories;

use Illuminate\Support\Collection;
use Modules\Event\DTOs\EventDto;
use Modules\Event\Models\Event;

class EventRepository implements IEventRepository
{
    public function getEvents():Collection
    {
        return Event::all();
    }

    public function getEvent(int $eventId): EventDto
    {
        $event = Event::find($eventId);

        return EventDto::fromEloquentModel($event);
    }


    public function updateEvent(Event $event): EventDto
    {
        $event->save();
        return EventDto::fromEloquentModel($event);
    }
}
