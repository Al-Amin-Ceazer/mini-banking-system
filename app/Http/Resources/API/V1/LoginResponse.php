<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * App\Http\Resources\API\V1\LoginResponse.
 *
 * @mixin \App\Models\Customer
 *
 * @property-read string api_token
 */
class LoginResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */

    public function toArray($request)
    {
        return null;
    }

    public function with($request)
    {
        return [
            'code'         => Response::HTTP_OK,
            'app_message'  => "login success, credentials match, {$this->email}",
            'user_message' => 'Log In Successful',
            'context'      => 'login',
            'access_token' => $this->api_token,
        ];
    }
}
