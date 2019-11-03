<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array
     */
    public function rules()
    {
        return [
            'from_account_id' => 'required|exists:accounts,id',
            'beneficiary_id'  => 'required|exists:beneficiaries,id',
            'amount'          => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'submit_token'    => 'required',
        ];
    }
}
