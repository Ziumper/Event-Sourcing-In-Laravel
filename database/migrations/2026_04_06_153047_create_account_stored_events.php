<?php

use App\Traits\StoredEventsMigrationTrait;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use StoredEventsMigrationTrait;
    
    protected function getTableName(): string {
        return 'account_stored_events';
    }
    
};
