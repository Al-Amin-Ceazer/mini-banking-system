<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class LoginRequest
 *
 * @package App\Http\Requests\API\V1
 */
class FundTransferStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $customer = $request->user();
        $customerAccounts = $customer->accounts->pluck('id')->toArray();
        $customerBeneficiaries = $customer->beneficiaries->pluck('id')->toArray();
        return [
            'from_account_id' => 'required|in:'.implode(',', $customerAccounts),
            'beneficiary_id'  => 'required|in:'.implode(',', $customerBeneficiaries),
            'amount'          => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'submit_token'    => 'required',
        ];
    }
}
