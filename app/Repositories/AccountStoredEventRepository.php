<?php

namespace App\Repositories;

use App\Models\AccountStoredEvent;
use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

class AccountStoredEventRepository extends EloquentStoredEventRepository
{
    public function __construct() {
         $this->storedEventModel = (string) AccountStoredEvent::class;
    }
}