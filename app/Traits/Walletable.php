<?php

namespace App\Traits;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Walletable
{
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
}