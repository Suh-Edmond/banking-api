<?php
namespace App\Http\Controllers;

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
        $created = $this->accountService->createAccount($createAccountRequest);
        $account = $this->accountService->getAccountInfo($created->id);

        return $this->sendResponse($account, "Bank Account created successfully", 201);
    }

    public function getUserAccounts($id)
    {
        $data = $this->accountService->getUserAccounts($id);

        return $this->sendResponse($data, "success", 200);
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
