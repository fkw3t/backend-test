<?php

namespace App\Services;

use App\Models\User;
use App\Models\Seller;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\ThirdNotificationService;
use PHPUnit\Framework\InvalidDataProviderException;
use App\Exceptions\UnauthorizedTransactionException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Exceptions\UnavailableTransactionAuthorizingServiceException;
use App\Models\Wallet;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private WalletRepositoryInterface $walletRepository,
        private UserRepositoryInterface $userRepository,
        private SellerRepositoryInterface $sellerRepository,
        private ThirdNotificationService $notificationService,
    ){}

    public function transfer(array $payload): void
    {
        $this->checkPayer();
        $this->checkTransactionAuthorization();

        $payload['payer_id'] = Auth::user()->id;
        $payload['payer_wallet_id'] = $this->getProviderWallet($payload['payer_id']);
        $payload['payee_wallet_id'] = $this->getProviderWallet($payload['payee_id']);

        $transaction = DB::transaction(function () use($payload): Transaction {

            $transaction = $this->transactionRepository->create($payload);
            $x = $transaction->payerWallet->withdraw($payload['amount']);
            $y = $transaction->payeeWallet->deposit($payload['amount']);

            return $transaction;
        });

        $this->notificationService->handle($transaction);
    }

    private function checkPayer(): void
    {
        if(Auth::guard('seller')->user()){
            throw new InvalidDataProviderException('Sellers cant make transfers', 403);
        }
    }

    private function checkTransactionAuthorization(): void
    {
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
    
        if($response->failed()){
            throw new UnavailableTransactionAuthorizingServiceException('Transaction authorizing service is unavailable', 503);
        }
        if($response->json('message') != 'Autorizado'){
            throw new UnauthorizedTransactionException('Your transaction was not authorized', 403);
        }
    }

    private function getProviderWallet(string $providerId): string
    {
        return $this->walletRepository->findByOwner($providerId)->id;
    }
}
