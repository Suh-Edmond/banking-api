<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferType extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'name',
        'description'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
