<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getUser(string $id):object
    {
        return User::find($id);
    }

    public function listUsers(): array|Collection
    {
        return User::all();
    }

    public function createUser(array $data): bool|object
    {
        return User::create($data);
    }

    public function editUser(string $id, array $data): bool
    {
        $user = User::find($id);

        return $user->update($data);
    }

    public function deleteUser(string $id): bool
    {
        return User::delete($id);
    }
}