<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;


interface UserRepositoryInterface
{
    public function getUser(string $id): object;
    public function listUsers(): array|Collection;
    public function createUser(array $data): bool|object;
    public function editUser(string $id, array $data): bool;
    public function deleteUser(string $id): bool;
}