<?php

namespace App\Repositories\Eloquent;

use App\Models\Seller;
use App\Repositories\Contracts\SellerRepositoryInterface;
use Illuminate\Support\Collection;

class SellerRepository implements SellerRepositoryInterface
{
    public function get(string $sellerId): ?object
    {
        return Seller::find($sellerId);
    }

    public function listAll(): array|Collection
    {
        return Seller::all();
    }

    public function create(array $data): bool|object
    {
        return Seller::create($data);
    }

    public function edit(string $sellerId, array $data): bool|object
    {
        $seller = Seller::find($sellerId);
        return $seller->update($data);
    }

    public function delete(string $sellerId): bool
    {
        return Seller::delete($sellerId);
    }
}
