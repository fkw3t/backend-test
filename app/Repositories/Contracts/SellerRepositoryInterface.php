<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SellerRepositoryInterface
{
    public function getSeller(string $id): object;
    public function listSellers(): array|Collection;
    public function createSeller(array $data): bool|object;
    public function editSeller(string $id, array $data): bool|object;
    public function deleteSeller(string $id): bool;
}