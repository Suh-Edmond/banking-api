<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Account extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'user_id',
        'account_type',
        'status',
        'current_balance',
        'available_balance',
        'account_number'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
