<?php
namespace App\Services\Transaction;

use App\Interfaces\Transaction\TransferTypeInterface;
use App\Models\TransferType;

class TransferTypeService implements TransferTypeInterface {

    public function getTransferTypes()
    {
        return TransferType::all();
    }
}
