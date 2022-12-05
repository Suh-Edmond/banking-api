<?php

namespace Tests\Feature;

use App\Constants\AccountStatus;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_createAccount_should_throw_validation_error_when_exist_field_are_provided()
    {
        $this->withoutMiddleware();

        $response = $this->post("/api/protected/accounts", [
            'user_id'         => '',
            'account_type_id' => '',
            'telephone'     => '435904523'
        ]);

        $response->assertSessionHasErrors('user_id');
        $response->assertSessionHasErrors('account_type_id');
    }


    public function test_createAccount_should_create_account_when_all_fields_are_provided()
    {
        $this->withoutMiddleware();

        User::factory()->create();
        AccountType::factory()->create();

        $user = User::first();
        $accountType =  AccountType::first();

        $userById = User::find($user->id);
        $accountTypeById = AccountType::find($accountType->id);

        $response = $this->post("/api/protected/accounts", [
            'user_id'         => $userById->id,
            'account_type_id' => $accountTypeById->id,
            'telephone'     => '435904523'
        ]);

        $response->assertOk();
        $response->assertJson([
            'code' => 201,
            'message' => 'Bank Account created successfully'
        ]);
        $this->assertCount(1, Account::all());
    }

    public function test_getUserAccounts_should_return_a_user_bank_account_when_exist()
    {
        $this->withoutMiddleware();

        User::factory()->create();
        $user = User::first();

        AccountType::factory()->create();
        $accountType = AccountType::first();

        Account::factory([
            'user_id'         => $user->id,
            'account_type_id' => $accountType->id
        ])->create();

        $userById = User::find($user->id);

        $response = $this->get('/api/protected/accounts/users/'.$userById->id);

        $data = json_decode($response->getContent());

        $response->assertOk();

        $this->assertEquals(1, count($data->data));
        $this->assertEquals($data->data[0]->user_name, $userById->first_name." ".$userById->last_name);
        $this->assertEquals($data->data[0]->email, $userById->email);
        $this->assertEquals($data->data[0]->user_telephone, $userById->telephone);

        $this->assertEquals($data->data[0]->current_balance, 10000.0);
        $this->assertEquals($data->data[0]->available_balance, 10000.0);
        $this->assertEquals($data->data[0]->account_status, AccountStatus::ACTIVE);
        $this->assertNotNull($data->data[0]->account_number);
        $this->assertEquals($data->data[0]->account_type, $accountType->name);
        $this->assertNotNull($data->data[0]->account_telephone);
        $this->assertNotNull($data->data[0]->id);
        $this->assertEquals($data->data[0]->currency, 'XAF');
    }


    public function test_getAccountInfo_should_return_account_info_when_exist()
    {
        $this->withoutMiddleware();

        User::factory()->create();
        $user = User::first();

        AccountType::factory()->create();
        $accountType = AccountType::first();

        $createdAcount = Account::factory([
                        'user_id'         => $user->id,
                        'account_type_id' => $accountType->id
                    ])->create();


        $accountById = Account::find($createdAcount->id);

        $response = $this->get('/api/protected/accounts/'.$accountById->id);

        $data = json_decode($response->getContent());

        $response->assertOk();
        $response->assertStatus(200);

        $this->assertEquals($data->data->user_name, $user->first_name." ".$user->last_name);
        $this->assertEquals($data->data->email, $user->email);
        $this->assertEquals($data->data->user_telephone, $user->telephone);

        $this->assertEquals($data->data->current_balance, 10000.0);
        $this->assertEquals($data->data->available_balance, 10000.0);
        $this->assertEquals($data->data->account_status, AccountStatus::ACTIVE);
        $this->assertNotNull($data->data->account_number);
        $this->assertEquals($data->data->account_type, $accountType->name);
        $this->assertNotNull($data->data->account_telephone);
        $this->assertNotNull($data->data->id);
        $this->assertEquals($data->data->currency, 'XAF');
    }


    public function test_checkAccountBalance_should_return_an_account_balance_when_account_exist()
    {
        $this->withoutMiddleware();

        User::factory()->create();
        $user = User::first();

        AccountType::factory()->create();
        $accountType = AccountType::first();

        $createdAcount = Account::factory([
                        'user_id'         => $user->id,
                        'account_type_id' => $accountType->id
                    ])->create();
        $accountByAccountNumber = Account::where('account_number', $createdAcount->account_number)->get();

        //getting the first object in the array
        $response = $this->post('/api/protected/account/check-balance', [
            'account_number' => $accountByAccountNumber[0]->account_number
        ]);

        $data = json_decode($response->getContent());

        $response->assertOk();
        $response->assertStatus(200);

        $this->assertEquals($data->data->telephone, $accountByAccountNumber[0]->telephone);
        $this->assertEquals($data->data->account_number, $accountByAccountNumber[0]->account_number);
        $this->assertEquals($data->data->account_type, $accountType->name);
        $this->assertEquals($data->data->current_balance, $accountByAccountNumber[0]->current_balance);
        $this->assertEquals($data->data->available_balance, $accountByAccountNumber[0]->available_balance);
        $this->assertEquals($data->data->currency, $accountByAccountNumber[0]->currency);

    }

    public function test_checkAccountBalance_should_throw_validation_error_when_account_number_not_provided()
    {
        $this->withoutMiddleware();

        $response = $this->post('/api/protected/account/check-balance', [
            'account_number' => ''
        ]);

        $response->assertSessionHasErrors('account_number');

    }

}
