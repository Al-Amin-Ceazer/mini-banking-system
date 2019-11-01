<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\CustomerToken;
use Illuminate\Support\Facades\Hash;

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
        $customer = new Customer();

        $customer->first_name    = $data['first_name'];
        $customer->last_name     = $data['last_name'];
        $customer->email         = $data['email'];
        $customer->personal_code = $data['username'];
        $customer->password      = Hash::make($data['password']);

        if (!$customer->save()) {
            return false;
        }

        return $customer;
    }

    protected function generatePersonalCode($customer_id)
    {
        $dateString = date('Ymd'); //Generate a datestring.
        $branchNumber = 101; //Get the branch number somehow.
        $receiptNumber = $customer_id;

        if($receiptNumber < 9999) {

            $receiptNumber = $receiptNumber + 1;

        }else{
            $receiptNumber = 1;
        }
        return $dateString . '-' . $branchNumber . '-' . $receiptNumber;
    }

}
