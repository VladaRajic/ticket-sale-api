<?php

namespace Modules\Event\Services;

use Illuminate\Support\Collection;
use Modules\Event\DTOs\EventDto;

interface IEventService
{
    public function getEvents(): Collection;

    public function getEvent(int $eventId): EventDto;
}
