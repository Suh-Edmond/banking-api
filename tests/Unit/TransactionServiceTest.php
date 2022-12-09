<?php

namespace Tests\Unit;

use App\Services\Transaction\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private TransactionService $transactionService;
    public function setUp(): void
    {
        parent::setUp();
        $this->transactionService = new TransactionService();
    }

    public function testInitiateTransferShouldThrowNotFoundExceptionWhenAccountNumberFromNotExist()
    {

    }
}
