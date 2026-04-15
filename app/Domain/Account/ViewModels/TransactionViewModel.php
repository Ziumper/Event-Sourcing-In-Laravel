<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace App\Domain\ViewModels;

use App\Domain\Account\Events\MoneyAdded;
use App\Domain\Account\Events\MoneySubtracted;
use Carbon\CarbonImmutable;

class TransactionViewModel {
    
    public function __construct(
            private float $amount, 
            private CarbonImmutable $when, 
            private float $transactionAmount,
            private string $type
    ) {
        
        $this->type = match ($this->type) {
            MoneyAdded::class => "Deposit",
            MoneySubtracted::class => "Withdrawn",
            default => 'not known'
        };
    }
    
    public function toArray(): array {
        return [
            "amount" => $this->amount,
            "when" => $this->when,
            "transactionAmount" => $this->transactionAmount,
            "type"=> $this->type,
        ];
    }
    
}
