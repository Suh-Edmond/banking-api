<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'transaction_code',
        'amount_deposited',
        'transaction_date',
        'motive',
        'transfer_type_id',
        'account_number_from',
        'account_number_to',
        'status',
    ];

    public function transferType()
    {
        return $this->belongsTo(TransferType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
