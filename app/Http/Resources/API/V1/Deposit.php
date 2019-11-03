<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\Resource;

class Deposit extends Resource
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
            'account_id'  => $this->account_id,
            'amount'      => $this->amount,
            'date'        => $this->date_time,
            'description' => $this->description,
        ];
    }
}
