<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * Class LoginRequest
 *
 * @package App\Http\Requests\API\V1
 */
class DepositStoreRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $customer = $request->user();
        $customerAccounts = $customer->accounts->pluck('id')->toArray();

        return [
            'account_id'   => 'required|in:'.implode(',', $customerAccounts),
            'amount'       => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'submit_token' => 'required',
        ];
    }
}
