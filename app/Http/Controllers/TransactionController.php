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
use App\Exceptions\UnavailableTransactionServiceException;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $service
    ){}

    /**
     * @OA\Post(
     *  tags={"transactions"},
     *  path="/api/transaction/transfer",
     *  operationId="transaction",
     *  summary="make a transfer",
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="payee_id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     type="decimal"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
     *                 example={
     *                          "payee_id": "4d17e89f-e3c6-468a-8c86-3f9ce3860697",
     *                          "amount": 30.5,
     *                          "description": "some transaction"
     *                  }
     *             )
     *         )
     *     ),
     *  @OA\Response(response="200",
     *    description="Success",
     *  ),
     *  @OA\Response(response="401",
     *    description="Insufficient balance account",
     *  ),
     *  @OA\Response(response="403",
     *    description="Sellers cant make transfers | You transaction was not authorized",
     *  ),
     *  @OA\Response(response="503",
     *    description="Transaction authorizing service is unavailable | Notification service is unavailable now",
     *  ),
     *  security={{ "apiAuth": {} }},
     * )
     */
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
        } catch (UnavailableTransactionServiceException $e) {
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
