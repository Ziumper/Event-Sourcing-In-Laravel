<?php

namespace App\Models;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class AccountStoredEvent extends EloquentStoredEvent
{
    protected $table = 'account_stored_events';
}