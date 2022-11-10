<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Seller;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    # todo: add setup method;

    // public function test_unauthenticated_provider_couldnt_make_transfer(): void
    // {
    //     // arrange
    //     $seller = Seller::factory()
    //         ->create();
    //     $sellerWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $seller->id,
    //             'walletable_type' => Seller::class,
    //         ]);

    //     $user = User::factory()
    //         ->create();
    //     $userWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $user->id,
    //             'walletable_type' => User::class,
    //             'balance' => 100
    //         ]);

    //     // act
    //     $response = $this->post('/api/transaction/transfer', [
    //         'payee_id' => $seller->id,
    //         'amount' => 50,
    //         'description' => 'some description'
    //     ]);

    //     // assert
    //     $response->assertStatus(401);
    // }

    public function test_seller_couldnt_make_transfer(): void
    {
        // arrange
        $seller = Seller::factory()
            ->create();
        $sellerWallet = Wallet::factory()
            ->create([
                'walletable_id' => $seller->id,
                'walletable_type' => Seller::class,
                'balance' => 200
            ]);

        $user = User::factory()
            ->create();
        $userWallet = Wallet::factory()
            ->create([
                'walletable_id' => $user->id,
                'walletable_type' => User::class
            ]);

        $token = 'Bearer ' . Auth::login($seller);

        // act
        $response = $this->withHeader('Authorization', $token)
            ->post('/api/transaction/transfer', [
                'payee_id' => $user->id,
                'amount' => 150,
                'description' => 'some description'
            ]);

        // assert
        $response->assertStatus(403)
            ->assertJson(['message' => 'Sellers cant make transfers']);
    }

    // // todo: neeed to mock mock service
    // public function test_authorizing_transaction_service_should_deny(): void
    // {
    //     // arrange
    //     $seller = Seller::factory()
    //         ->create();
    //     $sellerWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $seller->id,
    //             'walletable_type' => Seller::class,
    //         ]);

    //     $user = User::factory()
    //         ->create();
    //     $userWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $user->id,
    //             'walletable_type' => User::class,
    //             'balance' => 200
    //         ]);

    //     $token = 'Bearer ' . Auth::login($user);

    //     // act
    //     $response = $this->withHeader('Authorization', $token)
    //         ->post('/api/transaction/transfer', [
    //             'payee_id' => $seller->id,
    //             'amount' => 150,
    //             'description' => 'some description'
    //         ]);

    //     // assert
    //     $response->assertStatus(403)
    //         ->assertJson(['message' => 'Your transaction was not authorized']);
    // }

    // // todo: neeed to mock mock service
    // public function test_authorizing_transaction_service_is_down(): void
    // {
    //     // arrange
    //     $seller = Seller::factory()
    //         ->create();
    //     $sellerWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $seller->id,
    //             'walletable_type' => Seller::class,
    //         ]);

    //     $user = User::factory()
    //         ->create();
    //     $userWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $user->id,
    //             'walletable_type' => User::class,
    //             'balance' => 200
    //         ]);

    //     $token = 'Bearer ' . Auth::login($user);

    //     // act
    //     $response = $this->withHeader('Authorization', $token)
    //         ->post('/api/transaction/transfer', [
    //             'payee_id' => $seller->id,
    //             'amount' => 150,
    //             'description' => 'some description'
    //         ]);

    //     // assert
    //     $response->assertStatus(503)
    //         ->assertJson(['message' => 'Transaction authorizing service is unavailable']);
    // }

    public function test_user_with_enough_balance_should_get_exception(): void
    {
        // arrange
        $seller = Seller::factory()
            ->create();
        $sellerWallet = Wallet::factory()
            ->create([
                'walletable_id' => $seller->id,
                'walletable_type' => Seller::class,
            ]);

        $user = User::factory()
            ->create();
        $userWallet = Wallet::factory()
            ->create([
                'walletable_id' => $user->id,
                'walletable_type' => User::class,
                'balance' => 0
            ]);

        $token = 'Bearer ' . Auth::login($user);

        // act
        $response = $this->withHeader('Authorization', $token)
            ->post('/api/transaction/transfer', [
                'payee_id' => $seller->id,
                'amount' => 50,
                'description' => 'some description'
            ]);

        // assert
        $this->assertEquals(Wallet::find($userWallet->id)->balance, 0);
        $this->assertEquals(Wallet::find($sellerWallet->id)->balance, 0);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Insufficient balance account']);
    }

    // todo: neeed to mock mock service
    // public function test_notification_service_should_be_unavailable(): void
    // {
    //     // arrange
    //     $seller = Seller::factory()
    //         ->create();
    //     $sellerWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $seller->id,
    //             'walletable_type' => Seller::class,
    //         ]);

    //     $user = User::factory()
    //         ->create();
    //     $userWallet = Wallet::factory()
    //         ->create([
    //             'walletable_id' => $user->id,
    //             'walletable_type' => User::class,
    //             'balance' => 100
    //         ]);

    //     $token = 'Bearer ' . Auth::login($user);

    //     // act
    //     $response = $this->withHeader('Authorization', $token)
    //         ->post('/api/transaction/transfer', [
    //             'payee_id' => $seller->id,
    //             'amount' => 50,
    //             'description' => 'some description'
    //         ]);

    //     // assert
    //     // transfer should be commited even notification fails
    //     $this->assertEquals(Wallet::find($userWallet->id)->balance, 50);
    //     $this->assertEquals(Wallet::find($sellerWallet->id)->balance, 50);

    //     $response->assertStatus(503)
    //         ->assertJson(['message' => 'Notification service is unavailable now']);
    // }

    public function test_should_transfer(): void
    {
        // arrange
        $seller = Seller::factory()
            ->create();
        $sellerWallet = Wallet::factory()
            ->create([
                'walletable_id' => $seller->id,
                'walletable_type' => Seller::class,
            ]);

        $user = User::factory()
            ->create();
        $userWallet = Wallet::factory()
            ->create([
                'walletable_id' => $user->id,
                'walletable_type' => User::class,
                'balance' => 100
            ]);

        $token = 'Bearer ' . Auth::login($user);

        // act
        $response = $this->withHeader('Authorization', $token)
            ->post('/api/transaction/transfer', [
                'payee_id' => $seller->id,
                'amount' => 50,
                'description' => 'some description'
            ]);

        // assert
        // transfer should be commited even notification fails
        $this->assertEquals(Wallet::find($userWallet->id)->balance, 50);
        $this->assertEquals(Wallet::find($sellerWallet->id)->balance, 50);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successful transaction']);
    }
}
