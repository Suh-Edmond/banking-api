<?php

namespace Tests\Feature;

use App\Models\AccountType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_getAccountTypes_should_return_all_available_account_types()
    {
        $this->withoutMiddleware();

        AccountType::factory()->count(5)->create();

        $accountTypes = AccountType::all();

        $response = $this->get('/api/protected/account-types');

        $response->assertStatus(200);
        $this->assertEquals(5, count($accountTypes));
    }
}
