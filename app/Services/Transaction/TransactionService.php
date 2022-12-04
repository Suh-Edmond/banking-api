<?php

namespace App\Services\Transaction;

use App\Exceptions\InsufficientAccountBalanceException;
use App\Http\Resources\TransactionHistoryResource;
use App\Interfaces\Transaction\TransactionInterface;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransferType;
use App\Traits\CodeGenerationTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class TransactionService implements TransactionInterface {

    use CodeGenerationTrait;

    public function initiateTransfer($request)
    {
        $accountNumberFrom = Account::where('account_number', $request->account_number_from)->first();
        if(is_null($accountNumberFrom)) {
            throw new ResourceNotFoundException("account_number_from not found");
        }
        $accountNumberTo = Account::where('account_number', $request->account_number_to)->first();
        if(is_null($accountNumberTo)) {
            throw new ResourceNotFoundException("account_number_to not found");
        }
        $transferType  = TransferType::find($request->transfer_type_id)->first();
        if(is_null($transferType)) {
            throw new ResourceNotFoundException("Invalid transfer_type_id");
        }
        if(($accountNumberFrom->available_balance - $request->amount_deposited) < 1000.0){
            throw new Exception("Cannot perform a transfer, Insufficient balance.", 424);
        }
        try {
            DB::transaction(function () use ($request, $transferType, $accountNumberFrom, $accountNumberTo) {
                Transaction::create([
                    'account_number_from'   => $request->account_number_from,
                    'account_number_to'     => $request->account_number_to,
                    'amount_deposited'      => $request->amount_deposited,
                    'transaction_date'      => $request->transaction_date,
                    'transaction_code'      => $this->generateTransactionCode(),
                    'motive'                => $request->motive,
                    'transfer_type_id'      => $transferType->id,
                ]);

                $accountNumberFrom->current_balance   = ($accountNumberFrom->current_balance - $request->amount_deposited);
                $accountNumberFrom->available_balance = ($accountNumberFrom->available_balance - $request->amount_deposited);
                $accountNumberFrom->save();


                $accountNumberTo->current_balance     =  ($accountNumberTo->current_balance + $request->amount_deposited);
                $accountNumberTo->available_balance   =  ($accountNumberTo->available_balance + $request->amount_deposited);
                $accountNumberTo->save();

            });
        }catch(Exception $e){
            throw new Exception("Error occurred! Could not perform transfer");
        }
    }

    public function retrieveAccountTransactions($request)
    {
        $transaction = Transaction::where('account_number_from', $request->account_number)
                                ->orWhere('account_number_to', $request->account_number)
                                ->orderBy('created_at', 'DESC')
                                ->get();

        return TransactionHistoryResource::collection($transaction);
    }


}
