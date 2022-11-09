<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Exceptions\UnavailableBalanceException;

class WalletTest extends TestCase
{
    public function test_wallet_withdraw_should_be_applied_to_balance(): void
    {
        // arrange
        $userWallet = Wallet::factory()->create(['balance' => 50]);

        // act
        $userWallet->withdraw(30);

        // assert
        $this->assertSame($userWallet->balance,20.0);
    }

    public function test_wallet_withdraw_amount_that_doenst_contain_in_balance_should_throw_exception(): void
    {
        // arrange
        $userWallet = Wallet::factory()->create();

        // assert
        $this->expectException(UnavailableBalanceException::class);
        $this->expectExceptionMessage('Insufficient balance account');
        $this->expectExceptionCode(401);
        
        // act
        $userWallet->withdraw(50);

    }

    public function test_wallet_deposit_should_be_applied_to_balance(): void
    {
        // arrange
        $userWallet = Wallet::factory()->create();

        // act
        $userWallet->deposit(50);

        // assert
        $this->assertSame($userWallet->balance,50.0);
    }
}
