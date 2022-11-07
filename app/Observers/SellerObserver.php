<?php

namespace App\Observers;

use Ramsey\Uuid\Uuid;
use App\Models\Seller;

class SellerObserver
{
    public function creating(Seller $seller): void
    {
        $seller->id = Uuid::uuid4();
    }
}
