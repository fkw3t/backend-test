<?php

namespace App\Observers;

use Ramsey\Uuid\Uuid;
use App\Models\Transaction;

class TransactionObserver
{
    public function creating(Transaction $transaction): void
    {
        $transaction->id = Uuid::uuid4();
    }
}
