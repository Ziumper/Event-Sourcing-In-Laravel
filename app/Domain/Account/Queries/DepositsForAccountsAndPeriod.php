<?php

use App\Domain\Account\Events\MoneyAdded;
use Spatie\EventSourcing\EventHandlers\Projectors\EventQuery;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class DepositsForAccountsAndPeriod extends EventQuery
{
    private array $deposits;
    
    public function __construct(
            private readonly string $startDate, 
            private readonly string $endDate) 
    {
        EloquentStoredEvent::query()
            // We're only interested in `MoneyAdded` events
            ->whereEvent(MoneyAdded::class)
            // And we only need events within a given period
            ->whereDate(
                'created_at', '>=', $this->startDate
            )
            ->whereDate(
                'created_at', '<=', $this->endDate
            )
            ->each(
                fn (EloquentStoredEvent $event) => $this->apply($event->toStoredEvent())
            );
    }
    
    protected function applyListOfDeposits(MoneyAdded $addedMoney): void 
    {
        $this->deposits[] = [
            "amount" => $addedMoney->amount,
            "when" => $addedMoney->createdAt()
        ];
    }
    
    public function getDeposits(): array {
        return $this->deposits;
    }
}