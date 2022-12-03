<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'transfer_id',
        'transaction_code',
        'amount',
        'transaction_date',
        'motive',
        'transfer_type_id'
    ];

    public function transferType()
    {
        return $this->belongsTo(TransferType::class);
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
