<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankingTransaction extends Model
{
    protected $dates = ['date_time'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
