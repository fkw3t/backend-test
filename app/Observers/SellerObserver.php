<?php

namespace App\Observers;

use Ramsey\Uuid\Uuid;
use App\Models\Seller;

class SellerObserver
{
    public function created(Seller $seller): void
    {
        // 
    }

    public function creating(Seller $seller): void
    {
        $seller->id = Uuid::uuid4();
    }

    public function updated(Seller $seller): void
    {
        //
    }

    public function deleted(Seller $seller): void
    {
        //
    }

    public function restored(Seller $seller): void
    {
        //
    }

    public function forceDeleted(Seller $seller): void
    {
        //
    }
}
