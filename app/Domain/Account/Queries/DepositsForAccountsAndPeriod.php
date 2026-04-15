<?php

use App\Domain\Account\Events\MoneyAdded;
use App\Domain\Account\Events\MoneySubtracted;
use App\Domain\ViewModels\TransactionViewModel;
use App\Models\AccountStoredEvent;
use Spatie\EventSourcing\EventHandlers\Projectors\EventQuery;

class DepositsForAccountsAndPeriod extends EventQuery
{
    /**
     * 
     * @var TransactionViewModel[] 
     */
    private array $deposits = [];
    private int $momentAccountBalance = 0;
    
    public function __construct(
            private readonly string $startDate, 
            private readonly string $endDate) 
    {
        AccountStoredEvent::query()
            // We're only interested in `MoneyAdded` events
            ->whereEvent(MoneyAdded::class, MoneySubtracted::class)
            // And we only need events within a given period
            ->each(
                fn (AccountStoredEvent $event) => $this->apply($event->toStoredEvent())
            );
    }
    
    protected function applyListOfDeposits(MoneyAdded $addedMoney): void 
    {
        $this->momentAccountBalance += $addedMoney->amount;

        $this->deposits[] = new TransactionViewModel(
                $this->momentAccountBalance, 
                $addedMoney->createdAt(),
                $addedMoney->amount,
                MoneyAdded::class
        )->toArray();
    }
    
    protected function applyListOfSubtractEvents(MoneySubtracted $subtractedMoney): void
    {
        $this->momentAccountBalance -= $subtractedMoney->amount;
        
        $this->deposits[] = new TransactionViewModel(
                $this->momentAccountBalance, 
                $subtractedMoney->createdAt(),
                $subtractedMoney->amount,
                MoneySubtracted::class
        )->toArray();
    }


    public function getDeposits(): array {
        return $this->deposits;
    }
    
    
}