<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use App\Exceptions\UnavailableNotificationServiceException;

class ThirdNotificationService
{
    public function handle(Transaction $transaction): void
    {
        // implementation

        $this->mockNotification();
    }

    public function mockNotification(): void
    {
        $response = Http::get('http://o4d9z.mocklab.io/notify');

        if($response->json('message') != 'Success' || $response->failed()){
            throw new UnavailableNotificationServiceException('Notification service is unavailable now', 503);
        }
    }
}