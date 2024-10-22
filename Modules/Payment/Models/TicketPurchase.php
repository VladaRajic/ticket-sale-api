<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Event\Models\Event;

class TicketPurchase extends Model
{

    protected $fillable = ['event_id', 'email', 'transaction_id'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
