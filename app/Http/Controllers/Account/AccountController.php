<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckAccountBalanceRequest;
use App\Http\Requests\CreateAccountRequest;
use App\Services\Account\AccountService;

class AccountController extends Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }


    public function createAccount(CreateAccountRequest $createAccountRequest)
    {
        $data = $this->accountService->createAccount($createAccountRequest);

        return $this->sendResponse($data, "Banck Account created successfully", 201);
    }

    public function getAccountInfo($id)
    {
        $data = $this->accountService->getAccountInfo($id);

        return $this->sendResponse($data, "success", 200);
    }


    public function checkAccountBalance(CheckAccountBalanceRequest $request)
    {
        $data = $this->accountService->checkAccountBalance($request);

        return $this->sendResponse($data, "success", 200);
    }
}
