<?php

namespace App\Providers;

use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\SellerRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SellerRepositoryInterface::class, SellerRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
