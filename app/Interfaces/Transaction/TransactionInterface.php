<?php

namespace App\Interfaces\Transaction;

interface TransactionInterface  {

    public function initiateBankTransfer();

    public function checkAccountTransactions();

    public function getTransactionDetails();

}
