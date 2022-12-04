<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    // public function setUp()
    // {
    //     parent::setUp();
    // }

    // public function test_createAccount_should_create_account_when_all_field_are_provided()
    // {
    //     $response = $this->post('/api/protected/accounts', [
    //         "user_id"           => "7dd4fbcd-1ce6-475f-839b-6cb3c6349005",
    //         "account_type_id"   => "387dfa70-dfb4-4769-9fa4-a1719539701b",
    //         "telephone"         => "674650902"
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertCount(1, Account::all());
    // }
}
