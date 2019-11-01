<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
