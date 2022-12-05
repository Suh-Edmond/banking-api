<?php

namespace App\Services\AccountType;

use App\Interfaces\AccountType\AccountTypeInterface;
use App\Models\AccountType;

class AccountTypeService implements AccountTypeInterface {

    public function getAccountTypes()
    {
        return AccountType::all();
    }

}
