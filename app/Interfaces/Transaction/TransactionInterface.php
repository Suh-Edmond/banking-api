<?php

namespace App\Interfaces\Transaction;

interface TransactionInterface  {

    public function initiateTransfer($request);

    public function retrieveAccountTransactions($request);

}
