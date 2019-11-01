<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmitToken extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'submit_tokens';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function tokenable()
    {
        return $this->morphTo('tokenable');
    }
}
