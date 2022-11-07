<?php

namespace App\Observers;

use Ramsey\Uuid\Uuid;
use App\Models\Wallet;

class WalletObserver
{
    public function creating(Wallet $wallet): void
    {
        $wallet->id = Uuid::uuid4();
    }
}
