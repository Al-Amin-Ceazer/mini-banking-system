<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\Resource;

/**
 * App\Http\Resources\API\V1\Profile
 *
 * @mixin \App\User
 */
class Profile extends Resource
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
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'         => $this->email,
            'personal_code' => $this->personal_code,
        ];
    }
}
