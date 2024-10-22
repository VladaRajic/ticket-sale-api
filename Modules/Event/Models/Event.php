<?php

namespace Modules\Event\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Payment\Models\TicketPurchase;
use Modules\Venue\Models\Venue;

class Event extends Model
{

    protected $fillable = ['name', 'venue_id', 'available_tickets', 'ticket_sales_end_date'];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function availableTickets(): int
    {
        return $this->venue->capacity - $this->soldTickets();
    }

    public function ticketPurchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class);
    }

    public function soldTickets(): int
    {
        return $this->ticketPurchases()->count();
    }

    public function updateAvailableSeats(): int
    {
        $this->available_tickets = $this->availableTickets();
        $this->save();
        return $this->available_tickets;
    }
}
