<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function get(string $userId):object
    {
        return User::find($userId);
    }

    public function listAll(): array|Collection
    {
        return User::all();
    }

    public function create(array $data): bool|object
    {
        return User::create($data);
    }

    public function edit(string $userId, array $data): bool
    {
        $user = User::find($userId);

        return $user->update($data);
    }

    public function delete(string $userId): bool
    {
        return User::delete($userId);
    }
}
