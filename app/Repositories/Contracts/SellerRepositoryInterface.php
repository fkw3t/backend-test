<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SellerRepositoryInterface
{
    public function get(string $id): ?object;
    public function listAll(): array|Collection;
    public function create(array $data): bool|object;
    public function edit(string $id, array $data): bool|object;
    public function delete(string $id): bool;
}
