<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'name',
        'description'
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
