<?php

namespace App\Services;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\AuthorizationException;
use PHPUnit\Framework\InvalidDataProviderException;

final class AuthService
{
    public function register(string $provider, array $payload): object
    {
        $provider = $this->getProvider($provider);
        $payload['password'] = Hash::make($payload['password']);

        return $provider->create($payload);
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

    private function getProvider(string $provider): Model
    {
        if($provider === 'user'){
            return new User();
        }
        elseif($provider === 'seller'){
            return new Seller();
        }

        throw new InvalidDataProviderException('Invalid provider');
    }
}