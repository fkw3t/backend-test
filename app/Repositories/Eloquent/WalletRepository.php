<?php

namespace App\Repositories\Eloquent;

use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function findByOwner(string $providerId): ?object
    {
        return Wallet::where('walletable_id', $providerId)->first();
    }

    public function create(string $provider, string $providerId): bool|object
    {
        $wallet = new Wallet();
        $wallet->walletable_id = $providerId;
        $wallet->walletable_type = $provider;
        $wallet->walletable_type = $provider;

        return $wallet->save();
    }

    public function delete(string $provider, string $providerId): bool
    {
        return Wallet::where('walletable_type', $provider)
            ->where('walletable_id', $providerId)
            ->delete();
    }
}

