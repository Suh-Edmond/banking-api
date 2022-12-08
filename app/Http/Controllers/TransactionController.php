<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckTransactionHistoryRequest;
use App\Http\Requests\MoneyTransferRequest;
use App\Services\Transaction\TransactionService;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }


    public function initiateTransfer(MoneyTransferRequest $moneyTransferRequest): \Illuminate\Http\Response
    {
        $this->transactionService->initiateTransfer($moneyTransferRequest);

        return $this->sendResponse(null, "Transaction completed successfully", 201);
    }

    public function retrieveAccountTransactions(CheckTransactionHistoryRequest $request)
    {
        $data = $this->transactionService->retrieveAccountTransactions($request);

        return $this->sendResponse($data, 'success', 200);
    }
}
