<?php

namespace App\Http\Controllers;

use App\Services\AccountType\AccountTypeService;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    private $accountTypeService;

    public function __construct(AccountTypeService $accountTypeService)
    {
        $this->accountTypeService = $accountTypeService;
    }


    public function getAccountTypes()
    {
        $data = $this->accountTypeService->getAccountTypes();

        return $this->sendResponse($data, 'success', 200);
    }
}
