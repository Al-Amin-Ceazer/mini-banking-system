<?php

namespace App\Repositories;

use App\Models\SubmitToken;

class SubmitTokenRepository
{
    /**
     * @param      $token
     * @param null $type
     *
     * @return mixed
     */
    public function query($token, $type = null)
    {
        $thresholdTime = now()->subMonth(1)->toDateString();

        $entry = SubmitToken::whereToken($token)
                            ->where('created_at', '>=', $thresholdTime);
        if (!is_null($type)) {
            $entry->where('tokenable_type', '=', $type);
        }

        $entry = $entry->first();

        if (is_null($entry)) {
            return $entry;
        }

        return $entry->tokenable;
    }

    /**
     * @param $token
     * @param $type
     * @param $id
     */
    public function store($token, $type, $id)
    {
        $entry                 = new SubmitToken();
        $entry->tokenable_id   = $id;
        $entry->tokenable_type = $type;
        $entry->token          = $token;
        $entry->save();
    }
}
