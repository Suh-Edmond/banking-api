<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'transaction_number',
        'to_account_number',
        'from_account_number',
        'amount_deposited',
        'status',
        'account_id'
    ];


    public function account() {
        return $this->belongsTo(Account::class);
    }
}
