<?php

namespace App\Http\Controllers;

use App\Services\Transaction\TransferTypeService;
use Illuminate\Http\Request;

class TransferTypeController extends Controller
{
    private $transferTypeService;

    public function __construct(TransferTypeService $transferTypeService)
    {
        $this->transferTypeService = $transferTypeService;
    }


    public function getTransferTypes()
    {
        $data = $this->transferTypeService->getTransferTypes();

        return $this->sendResponse($data, 'success', 200);
    }
}
