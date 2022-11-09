<?php

namespace App\Repositories\Contracts;

interface WalletRepositoryInterface
{
    public function findByOwner(string $providerId): ?object;
    public function create(string $provider, string $providerId): bool|object;
    public function delete(string $provider, string $providerId): bool;
}
