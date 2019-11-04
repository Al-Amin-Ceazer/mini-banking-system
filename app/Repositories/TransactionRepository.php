<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\BankingTransaction;
use App\Models\Beneficiary;
use App\Models\FundTransfer;
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

        $account = Account::where('id', $data['account_id'])->first();

        try {
            DB::beginTransaction();
            $transaction->save();
            $transaction->account()->update([
                'account_balance' => $account->account_balance + $data['amount'],
            ]);

            DB::commit();

            return $transaction;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return false;
    }

    public function transfer($data)
    {
        $transaction                   = new BankingTransaction();
        $transaction->account_id       = $data['from_account_id'];
        $transaction->amount           = $data['amount'];
        $transaction->description      = 'Fund Transferred';
        $transaction->transaction_type = 'debit';
        $transaction->date_time        = Carbon::now();

        $account = Account::find($data['from_account_id']);

        $beneficiary = Beneficiary::find($data['beneficiary_id']);

        try {
            DB::beginTransaction();
            $transaction->save();
            $transaction->account()->update([
                'account_balance' => $account->account_balance - $data['amount'],
            ]);

            if ($beneficiary->transfer_type == 'same_bank') {
                $transactionTo                   = new BankingTransaction();
                $transactionTo->account_id       = $beneficiary->account_number;
                $transactionTo->amount           = $data['amount'];
                $transactionTo->description      = 'Fund Transferred';
                $transactionTo->transaction_type = 'credit';
                $transactionTo->date_time        = Carbon::now();

                $accountTo = Account::find($beneficiary->account_number)->first();

                $transactionTo->save();
                $transactionTo->account()->update([
                    'account_balance' => $accountTo->account_balance + $data['amount'],
                ]);
            }

            $fundTransfer                 = new FundTransfer();
            $fundTransfer->account_id     = $account->id;
            $fundTransfer->from_trans_id  = $transaction->id;
            $fundTransfer->to_trans_id    = $transactionTo->id ?? null;
            $fundTransfer->beneficiary_id = $beneficiary->id;
            $fundTransfer->remarks        = $data['remarks'] ?? '';
            $fundTransfer->save();

            DB::commit();

            return $transaction;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return false;
    }
}
