<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;


interface UserRepositoryInterface
{
    public function get(string $userId): ?object;
    public function listAll(): array|Collection;
    public function create(array $data): bool|object;
    public function edit(string $userId, array $data): bool;
    public function delete(string $userId): bool;
}
