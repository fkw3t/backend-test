<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function get(string $id):object
    {
        return User::find($id);
    }

    public function listAll(): array|Collection
    {
        return User::all();
    }

    public function create(array $data): bool|object
    {
        return User::create($data);
    }

    public function edit(string $id, array $data): bool
    {
        $user = User::find($id);

        return $user->update($data);
    }

    public function delete(string $id): bool
    {
        return User::delete($id);
    }
}
