<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransferRequest;
use App\Exceptions\UnavailableBalanceException;
use PHPUnit\Framework\InvalidDataProviderException;
use App\Exceptions\UnauthorizedTransactionException;
use App\Exceptions\UnavailableNotificationServiceException;
use App\Exceptions\UnavailableTransactionAuthorizingServiceException;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $service
    ){}

    public function transfer(TransferRequest $request): JsonResponse
    {
        try {
            $this->service->transfer($request->only([
                'payee_id',
                'amount',
                'description'
            ]));

            return response()->json([
                'message' => 'Successful transaction'
            ], 200);

        } catch (InvalidDataProviderException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        } catch (UnauthorizedTransactionException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);            
        } catch (UnavailableTransactionAuthorizingServiceException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 503);
        } catch (UnavailableBalanceException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);            
        } catch (UnavailableNotificationServiceException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 503);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
