<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'account_number_from',
        'account_number_to',
        'transfer_code'
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
