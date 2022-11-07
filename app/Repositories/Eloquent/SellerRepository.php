<?php

namespace App\Repositories\Eloquent;

use App\Models\Seller;
use App\Repositories\Contracts\SellerRepositoryInterface;
use Illuminate\Support\Collection;

class SellerRepository implements SellerRepositoryInterface
{
    public function getSeller(string $id): object
    {
        return Seller::find($id);
    }

    public function listSellers(): array|Collection
    {
        return Seller::all();
    }

    public function createSeller(array $data): bool|object
    {
        return Seller::create($data);
    }

    public function editSeller(string $id, array $data): bool|object
    {
        $seller = Seller::find($id);
        return $seller->update($data);
    }

    public function deleteSeller(string $id): bool
    {
        return Seller::delete($id);
    }
}