<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Transaction;
use App\Models\TransferType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;


    public function test_initiateTransfer_should_throw_validation_error_when_required_fields_are_not_provided()
    {
        $this->withoutMiddleware();

        $response = $this->post('/api/protected/accounts/transfer', [
            'account_number_to'     => '',
            'account_number_from'   => '',
            'amount_deposited'      => '1000',
            'transaction_date'      => '',
            'motive'                => '',
            'transfer_type_id'      => '',
        ]);

        $response->assertSessionHasErrorsIn('account_number_from');
        $response->assertSessionHasErrorsIn('account_number_to');
        $response->assertSessionHasErrorsIn('transaction_date');
        $response->assertSessionHasErrorsIn('transfer_type_id');
        $response->assertSessionHasErrorsIn('motive');
        $response->assertStatus(302);

    }

    public function test_initiateTransfer_should_initiate_transfer_when_required_fields_are_provided()
    {
        $this->withoutMiddleware();
        $this->withExceptionHandling();

        User::factory()->create();
        $user = User::first();

        AccountType::factory()->create();
        $accountType = AccountType::first();

        Account::factory([
                        'user_id'         => $user->id,
                        'account_type_id' => $accountType->id,
                        'telephone'       => "675923402" // provide another telephone number to keep unique constraint validation on the telephone field
                    ])->create();
        Account::factory([
                        'user_id'         => $user->id,
                        'account_type_id' => $accountType->id,
                        'telephone'       => "675923602" // provide another telephone number to keep unique constraint validation on the telephone field
                    ])->create();

        $createdAcounts = Account::all();

        $account_from = Account::where('account_number', $createdAcounts[0]->account_number)->first();
        $account_to   = Account::where('account_number', $createdAcounts[1]->account_number)->first();

        $savedTransferType =  TransferType::factory()->create();

        $transferType = TransferType::find($savedTransferType->id);

        $response = $this->post('/api/protected/accounts/transfer', [
            'account_number_to'     => $account_to->account_number,
            'account_number_from'   => $account_from->account_number,
            'amount_deposited'      => '1000.0',
            'transaction_date'      => '2022/12/12',
            'motive'                => 'Test Transfer between accounts',
            'transfer_type_id'      => $transferType->id,
        ]);

        $response->assertOk();
        $response->assertSeeText("Transaction completed successfully");

        $this->assertEquals(11000.0, $account_to->fresh()->current_balance);
        $this->assertEquals(11000.0, $account_to->fresh()->available_balance);

        $this->assertEquals(9000.0, $account_from->fresh()->current_balance);
        $this->assertEquals(9000.0, $account_from->fresh()->available_balance);

        $this->assertCount(1, Transaction::all());

    }

    public function test_retrieveAccountTransactions_should_return__all_transfer_made_by_an_account()
    {
        $this->withoutMiddleware();

        User::factory()->create();
        $user = User::first();

        AccountType::factory()->create();
        $accountType = AccountType::first();

        $account_from = Account::factory([
                            'user_id'         => $user->id,
                            'account_type_id' => $accountType->id,
                            'telephone'       => "675923402" // provide another telephone number to keep unique constraint validation on the telephone field
                        ])->create();
        $account_to =   Account::factory([
                            'user_id'         => $user->id,
                            'account_type_id' => $accountType->id,
                            'telephone'       => "675923602" // provide another telephone number to keep unique constraint validation on the telephone field
                        ])->create();
        $savedTransferType =  TransferType::factory()->create();

        Transaction::factory([
            'account_number_from' => $account_from->account_number,
            'account_number_to'   => $account_to->account_number,
            'transfer_type_id'    => $savedTransferType->id
        ])->count(5)->create();

        Transaction::where('account_number_from', $account_from->account_number)
                                    ->orWhere('account_number_to', $account_from->account_number)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
        $response = $this->post('/api/protected/accounts/transfer/histories', [
            'account_number'    => $account_from->account_number
        ]);

        $data = json_decode($response->getContent());

        $response->assertStatus(200);
        $response->assertOk();

        $this->assertCount(5, $data->data);
        $this->assertEquals($account_from->account_number, $data->data[0]->account_number_from);
        $this->assertEquals($account_to->account_number, $data->data[0]->account_number_to);
        $this->assertEquals(1000.0, $data->data[0]->amount_deposited);
        $this->assertEquals("Test transfer", $data->data[0]->motive);
        $this->assertEquals("COMPLETED", $data->data[0]->status);
        $this->assertEquals("2022/12/05", $data->data[0]->transaction_date);
        $this->assertNotNull($data->data[0]->transaction_code);
    }
}
