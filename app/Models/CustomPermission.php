<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class CustomPermission extends SpatiePermission
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing  = false;
    protected $keyType    = 'string';
}
