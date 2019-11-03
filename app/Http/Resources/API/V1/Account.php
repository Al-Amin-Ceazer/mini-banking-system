<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\Resource;

class Account extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'account_id'      => (int) $this->id,
            'account_type'    => $this->accountType->type,
            'branch'          => $this->branch->branch_name,
            'account_balance' => $this->account_balance,
        ];
    }
}
