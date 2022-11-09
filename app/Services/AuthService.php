<?php

namespace App\Services;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\AuthorizationException;
use PHPUnit\Framework\InvalidDataProviderException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;

final class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SellerRepositoryInterface $sellerRepository,
        private WalletRepositoryInterface $walletRepository
    ){}

    public function register(string $provider, array $payload): void
    {
        $payload['password'] = Hash::make($payload['password']);

        $provider = $this->getProvider($provider);
        $entity = $provider->create($payload);
        dd($this->walletRepository->create($provider::class, $entity->id));
        
    }

    public function login(string $provider, array $credentials)
    {
        $this->getProvider($provider);
        $token = Auth::guard($provider)->attempt($credentials);

        if(!$token){
            throw new AuthorizationException('Invalid credentials', 401);
        }

        return $token;
    }

    public function logout(string $provider): void
    {
        $this->getProvider($provider);
        Auth::guard($provider)->logout();
    }

    private function getProvider(string $provider): UserRepositoryInterface|SellerRepositoryInterface
    {
        if($provider === 'user'){
            return $this->userRepository;
        }
        elseif($provider === 'seller'){
            return $this->sellerRepository;
        }

        throw new InvalidDataProviderException('Invalid provider');
    }
}