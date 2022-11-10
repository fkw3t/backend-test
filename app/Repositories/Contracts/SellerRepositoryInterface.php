<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SellerRepositoryInterface
{
    public function get(string $sellerId): ?object;
    public function listAll(): array|Collection;
    public function create(array $data): bool|object;
    public function edit(string $sellerId, array $data): bool|object;
    public function delete(string $sellerId): bool;
}
