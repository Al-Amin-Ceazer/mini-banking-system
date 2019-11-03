<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Tests\AcceptHeaderTest;

class CustomerRepository
{
    public function find($id)
    {
        return Customer::find($id);
    }

    public function findByCredential($email, $personalCode)
    {
        return Customer::where('email', '=', $email)->where('personal_code', '=', $personalCode)->first();
    }

    public function findByApiToken($token)
    {
        $customerToken = CustomerToken::where('token', '=', $token)
                              ->first();

        if (empty($customerToken)) {
            return null;
        }

        return Customer::find($customerToken->customer_id);
    }

    public function registerApiToken($customer, $description)
    {
        $token              = new CustomerToken();
        $token->token       = bin2hex(random_bytes(32));
        $token->customer_id = $customer->id;
        $token->description = $description ?? '';
        $token->save();

        return [$customer, $token];
    }

    public function unregisterApiToken($customer, $token)
    {
        return CustomerToken::where('token', '=', $token)
                            ->where('customer_id', '=', $customer->id)
                            ->delete();
    }

    public function store($data)
    {
        $branch = Branch::where('branch_name','default')->first();
        $accountType = AccountType::where('type','savings')->first();
        $customer = new Customer();

        $customer->first_name    = $data['first_name'];
        $customer->last_name     = $data['last_name'];
        $customer->email         = $data['email'];
        $customer->password      = Hash::make($data['password']);

        $account = new Account();
        $account->branch_id = $branch->id;
        $account->account_type_id = $accountType->id;
        $account->account_balance = $accountType->minimum_balance_restriction;
        $account->date_opened = Carbon::today();
        $account->currency = 'Euro';

        try {
            DB::beginTransaction();
            $customer->save();
            $account->save();
            $customer->accounts()->attach($account->id);

            $customer->personal_code = $this->generatePersonalCode($customer->id,$branch->id);
            $customer->save();

            DB::commit();

            return $customer;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return false;
    }

    protected function generatePersonalCode($customerId,$branchId)
    {
        $dateString = date('Ymd'); //Generate a datestring.
        $branchNumber = 100+$branchId;
        $receiptNumber = $customerId;

        if($receiptNumber < 9999) {

            $receiptNumber = $receiptNumber + 1;

        }else{
            $receiptNumber = 1;
        }
        return $dateString . '-' . $branchNumber . '-' . $receiptNumber;
    }

}
