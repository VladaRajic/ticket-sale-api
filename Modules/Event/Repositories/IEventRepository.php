<?php

namespace Modules\Event\Repositories;

use Illuminate\Support\Collection;
use Modules\Event\DTOs\EventDto;
use Modules\Event\Models\Event;

interface IEventRepository
{
    public function getEvents(): Collection;

    public function getEvent(int $eventId): EventDto;

    public function updateEvent(Event $event): EventDto;
}
