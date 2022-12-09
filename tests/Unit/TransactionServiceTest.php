<?php

namespace Tests\Unit;

use App\Constants\TransactionStatus;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Transaction;
use App\Models\TransferType;
use App\Models\User;
use App\Services\Transaction\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tests\TestCase;
use Exception;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private TransactionService $transactionService;
    public function setUp(): void
    {
        parent::setUp();
        $this->transactionService = new TransactionService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testInitiateTransferShouldThrowNotFoundExceptionWhenAccountNumberFromNotExist()
    {
        $data = new Request([
            'account_number_from'   => '923092390',
            'account_number_to'     => '129230323',
            'motive'                => 'Test',
            'transaction_date'      => time(),
            'amount_deposited'      => 1000.0,
            'transfer_type_id'      => '294023-029302',
        ]);

        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['first'])->getMock();
        $accountMock->expects($this->once())->method('first')->willReturn(null);
        $accountMock->first();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("account_number_from not found");

        $this->transactionService->initiateTransfer($data);
    }

    public function testInitiateTransferShouldThrowNotFoundExceptionWhenAccountNumberToNotExist()
    {
        $data = new Request([
            'account_number_from'   => '923092390',
            'account_number_to'     => '129230323',
            'motive'                => 'Test',
            'transaction_date'      => time(),
            'amount_deposited'      => 1000.0,
            'transfer_type_id'      => '294023-029302',
        ]);
        $user = User::factory()->create();
        $accountType = AccountType::factory()->create();

        $accountNumberFrom = Account::factory([
            'account_number'        => $data['account_number_from'],
            'user_id'               => $user['id'],
            'account_type_id'       => $accountType['id']
        ])->create();

        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['first'])->getMock();
        $accountMock->expects($this->once())->method('first')->willReturn($accountNumberFrom);
        $accountMock->first();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("account_number_to not found");

        $this->transactionService->initiateTransfer($data);
    }

    public function testInitiateTransferShouldThrowNotFoundExceptionWhenTransferTypeNotFound()
    {
        $data = new Request([
            'account_number_from'   => '923092390',
            'account_number_to'     => '129230323',
            'motive'                => 'Test',
            'transaction_date'      => time(),
            'amount_deposited'      => 1000.0,
            'transfer_type_id'      => '294023-029302',
        ]);
        $user = User::factory()->create();
        $accountType = AccountType::factory()->create();

        $accountNumberFrom = Account::factory([
            'account_number'        => $data['account_number_from'],
            'user_id'               => $user['id'],
            'account_type_id'       => $accountType['id'],
            'telephone'             => '674908000'
        ])->create();

        $accountNumberTo = Account::factory([
            'account_number'        => $data['account_number_to'],
            'user_id'               => $user['id'],
            'account_type_id'       => $accountType['id'],
            'telephone'             => '612345678'
        ])->create();


        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['first'])->getMock();
        $accountMock->expects($this->exactly(2))->method('first')->willReturn([$accountNumberFrom, $accountNumberTo]);
        $accountMock->first();
        $accountMock->first();

        $transferTypeMock = $this->getMockBuilder(TransferType::class)->addMethods(['find'])->getMock();
        $transferTypeMock->expects($this->any())->method('find')->willReturn(null);
        $transferTypeMock->find($data['transfer_type_id']);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Invalid transfer_type_id");

        $this->transactionService->initiateTransfer($data);
    }

    public function testInitiateTransferShouldThrowNotFoundExceptionWhenInsufficientFunds()
    {

        $data = new Request([
            'account_number_from'   => '923092390',
            'account_number_to'     => '129230323',
            'motive'                => 'Test',
            'transaction_date'      => time(),
            'amount_deposited'      => 40000.0,
        ]);
        $user = User::factory()->create();

        $accountType = AccountType::factory()->create();

        $accountNumberFrom = Account::factory([
            'account_number'        => $data['account_number_from'],
            'user_id'               => $user['id'],
            'account_type_id'       => $accountType['id'],
            'telephone'             => '674908000',
            'current_balance'       => 10000.0,
            'available_balance'     => 10000.0
        ])->create();

        $accountNumberTo = Account::factory([
            'account_number'        => $data['account_number_to'],
            'user_id'               => $user['id'],
            'account_type_id'       => $accountType['id'],
            'telephone'             => '612345678',
            'current_balance'       => 10000.0,
            'available_balance'     => 10000.0
        ])->create();

        $transferType = TransferType::factory()->create();
        $data['transfer_type_id'] = $transferType['id'];

        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['first'])->getMock();
        $accountMock->expects($this->exactly(2))->method('first')->willReturn([$accountNumberFrom, $accountNumberTo]);
        $accountMock->first();
        $accountMock->first();

        $transferTypeMock = $this->getMockBuilder(TransferType::class)->addMethods(['find'])->getMock();
        $transferTypeMock->expects($this->once())->method('find')->will($this->returnValue($transferType));
        $transferTypeMock->find($data['transfer_type_id']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Cannot perform a transfer, Insufficient balance.");
        $this->transactionService->initiateTransfer($data);

    }

    public function testInitiateTransferShouldInitiateTransferWhenSufficientFunds()
    {

        $data = new Request([
            'account_number_from'   => '923092390',
            'account_number_to'     => '129230323',
            'motive'                => 'Test',
            'transaction_date'      => time(),
            'amount_deposited'      => 5000.0,
        ]);
        $user = User::factory()->create();

        $accountType = AccountType::factory()->create();

        $accountNumberFrom = Account::factory([
            'account_number'        => $data['account_number_from'],
            'user_id'               => $user['id'],
            'account_type_id'       => $accountType['id'],
            'telephone'             => '674908000',
            'current_balance'       => 10000.0,
            'available_balance'     => 10000.0
        ])->create();

        $accountNumberTo = Account::factory([
            'account_number'        => $data['account_number_to'],
            'user_id'               => $user['id'],
            'account_type_id'       => $accountType['id'],
            'telephone'             => '612345678',
            'current_balance'       => 10000.0,
            'available_balance'     => 10000.0
        ])->create();

        $transferType = TransferType::factory()->create();
        $data['transfer_type_id'] = $transferType['id'];

        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['first'])->getMock();
        $accountMock->expects($this->exactly(2))->method('first')->willReturn([$accountNumberFrom, $accountNumberTo]);
        $accountMock->first();
        $accountMock->first();

        $transferTypeMock = $this->getMockBuilder(TransferType::class)->addMethods(['find'])->getMock();
        $transferTypeMock->expects($this->once())->method('find')->will($this->returnValue($transferType));
        $transferTypeMock->find($data['transfer_type_id']);


        try {
            $this->transactionService->initiateTransfer($data);
        } catch (Exception $e) {
        }

        $this->assertDatabaseCount('transactions', 1);
        $this->assertDatabaseHas('transactions', [
            'account_number_from' => $accountNumberFrom['account_number'],
            'account_number_to'   => $accountNumberTo['account_number'],
            'amount_deposited'    => $data['amount_deposited'],
            'transaction_date'    => $data['transaction_date'],
            'motive'              => $data['motive'],
            'status'              => TransactionStatus::COMPLETED
        ]);

        $this->assertDatabaseHas('accounts', [
            'current_balance'       => 5000.0,
            'available_balance'     => 5000.0,
            'account_number'        => $accountNumberFrom['account_number']
        ]);

        $this->assertDatabaseHas('accounts', [
            'current_balance'       => 15000.0,
            'available_balance'     => 15000.0,
            'account_number'        => $accountNumberTo['account_number']
        ]);
    }

    public function testRetrieveAccountTransactionsShouldReturnAllTransfersByAccountType() {
        $user = User::factory()->create();

        $accountType = AccountType::factory()->create();

        $account = Account::factory([
            'user_id'            => $user['id'],
            'account_type_id'    => $accountType['id'],
            'account_number'     => '9103930123323'
        ])->create();

        $transferType = TransferType::factory()->create();

        $data = new Request([
            'account_number' => $account['account_number']
        ]);

        $transactions = Transaction::factory([
            'account_number_from'       => '90139012',
            'account_number_to'         => $data['account_number'],
            'transfer_type_id'          => $transferType['id']
        ])->count(5)->create();

        $mock = $this->getMockBuilder(Transaction::class)->addMethods(['get'])->getMock();
        $mock->expects($this->once())->method('get')->will($this->returnValue($transactions));
        $mock->get();

        $response = $this->transactionService->retrieveAccountTransactions($data);

        $this->assertEquals(5,count($response));
    }
}
