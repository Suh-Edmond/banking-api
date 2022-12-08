<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Services\Transaction\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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
        $data = new Request([
            'account_number_from'   => '83294032',
            'account_number_to'     => '90292',
            'amount_deposited'      => 1000.0,
            'transaction_date'      => '2022/12/08',
            'motive'                => 'Test',
            'transfer_type_id'      => '3892843',
        ]);

        $mock = $this->getMockBuilder(Account::class)->addMethods(['where'])->getMock();

        $mock->expects($this->once())->method('where')->willReturn(null);

        $mock->where($data['account_number_from']);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("account_number_from not found");

        try {
            $this->transactionService->initiateTransfer($data);
        } catch (\Exception $e) {
        }
    }
}
