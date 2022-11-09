<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;


interface UserRepositoryInterface
{
    public function get(string $id): ?object;
    public function listAll(): array|Collection;
    public function create(array $data): bool|object;
    public function edit(string $id, array $data): bool;
    public function delete(string $id): bool;
}
