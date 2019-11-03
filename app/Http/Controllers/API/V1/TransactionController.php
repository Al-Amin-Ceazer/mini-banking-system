<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\DepositStoreRequest;
use App\Http\Resources\API\ErrorResponse;
use App\Http\Resources\API\GenericResponse;
use App\Http\Resources\API\V1\Deposit;
use App\Models\BankingTransaction;
use App\Repositories\SubmitTokenRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\API\V1\Account as BalanceResponse;

class TransactionController extends Controller
{
    protected $transaction;
    protected $tokens;

    /**
     * TransactionController constructor.
     *
     * @param \App\Repositories\TransactionRepository $transaction
     * @param \App\Repositories\SubmitTokenRepository $tokens
     */
    public function __construct(TransactionRepository $transaction, SubmitTokenRepository $tokens)
    {
        $this->transaction = $transaction;
        $this->tokens      = $tokens;
    }

    public function accountBalance(Request $request)
    {
        $customer = $request->user();
        $accounts = $customer->accounts;

        return BalanceResponse::collection($accounts);
    }

    public function deposit(DepositStoreRequest $request)
    {
        $data        = $request->all();
        $submitToken = $request->input('submit_token');

        $newDeposit = $this->tokens->query($submitToken, BankingTransaction::class);

        if (empty($newDeposit)) {

            $newDeposit = $this->transaction->store($data);

            if ($newDeposit === false) {

                $message = [
                    'code'         => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'app_message'  => 'deposit store failed',
                    'user_message' => 'Deposit could not be stored!',
                    'submit_token' => $submitToken,
                ];

                return new ErrorResponse('deposit', $message, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($newDeposit->wasRecentlyCreated) {
                $this->tokens->store($submitToken, BankingTransaction::class, $newDeposit->id);
            }
        }

        $message = [
            'code'         => Response::HTTP_CREATED,
            'app_message'  => 'deposit store successful',
            'user_message' => 'Deposit store successfully!',
            'submit_token' => $submitToken,
        ];

        return new GenericResponse('deposit', new Deposit($newDeposit), $message, Response::HTTP_CREATED);
    }
}
