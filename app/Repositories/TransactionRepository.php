<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\BankingTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionRepository
{
    public function store($data)
    {
        $transaction                   = new BankingTransaction();
        $transaction->account_id       = $data['account_id'];
        $transaction->amount           = $data['amount'];
        $transaction->description      = $data['description'] ?? '';
        $transaction->transaction_type = 'credit';
        $transaction->date_time        = Carbon::now();

        $account = Account::where('id',$data['account_id'])->first();

        try {
            DB::beginTransaction();
            $transaction->save();
            $transaction->account()->update([
                'account_balance'=> $account->account_balance + $data['amount'],
            ]);

            DB::commit();

            return $transaction;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return false;
    }
}
