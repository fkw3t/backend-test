<?php

namespace App\Repositories\Contracts;

interface TransactionRepositoryInterface
{
    public function create(array $data): bool|object;
}
