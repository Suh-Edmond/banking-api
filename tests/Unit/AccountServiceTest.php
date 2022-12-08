<?php declare(strict_types=1);
namespace Tests\Unit;

use App\Constants\AccountStatus;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use App\Services\Account\AccountService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use DatabaseMigrations;


    private AccountService $accountService;
    private Request  $data;
    public function setUp(): void
    {
        parent::setUp();

        $this->accountService = new AccountService();

        $this->data = new Request(['telephone'       => '675009912']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_createAccount_should_create_an_account_when_user_account_exist_and_account_type_exist()
    {

        $user = User::factory()->create();
        $accountType = AccountType::factory()->create();

        $userMock = $mock = $this->getMockBuilder(User::class)->addMethods(['find'])->getMock();
        $accountTypeMock = $this->getMockBuilder(AccountType::class)->addMethods(['find'])->getMock();

        $userMock->expects($this->once())->method('find')->will($this->returnValue($user));
        $accountTypeMock->expects($this->once())->method('find')->will($this->returnValue($accountType));

        $this->data['account_type_id'] = $accountType['id'];
        $this->data['user_id']         = $user['id'];


        $userMock->find($this->data['user_id']);
        $accountTypeMock->find($this->data['account_type_id']);
        $this->accountService->createAccount($this->data);

        $this->assertDatabaseCount(Account::class, 1);
        $this->assertDatabaseHas(Account::class, ['user_id' => $this->data['user_id']]);
        $this->assertDatabaseHas(Account::class, ['account_type_id' => $this->data['account_type_id']]);
        $this->assertDatabaseHas(Account::class, ['current_balance' => 10000.00]);
        $this->assertDatabaseHas(Account::class, ['available_balance' => 10000.00]);
        $this->assertDatabaseHas(Account::class, ['status' => AccountStatus::ACTIVE]);
    }

    public function test_createAccount_should_throw_resource_not_found_exception_when_user_account_not_exist()
    {

        $this->data['account_type_id'] = '3403423';
        $this->data['user_id']         = '893434';

        $userMock = $this->getMockBuilder(User::class)->addMethods(['find'])->getMock();

        $userMock->expects($this->once())->method('find')->will($this->returnValue(null));

        $userMock->find($this->data['user_id']);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("User account not found");

        $this->accountService->createAccount($this->data);


    }

    public function test_createAccount_should_throw_resource_not_found_exception_when_account_type_not_exist()
    {
        $user = User::factory()->create();

        $this->data['account_type_id'] = '3403423';
        $this->data['user_id']         = $user['id'];

        $userMock = $this->getMockBuilder(User::class)->addMethods(['find'])->getMock();
        $accountTypeMock = $this->getMockBuilder(AccountType::class)->addMethods(['find'])->getMock();

        $userMock->expects($this->once())->method('find')->willReturn($user);
        $accountTypeMock->expects($this->once())->method('find')->willReturn(null);

        $userMock->find($this->data['user_id']);
        $accountTypeMock->find($this->data['account_type_id']);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Invalid Account type! The account_type_id does not exist");

        $this->accountService->createAccount($this->data);


    }

    public function test_getUserAccounts_should_throw_resource_not_found_when_user_does_not_exist()
    {
        $this->data['id'] = '9202323';
        $userMock = $this->getMockBuilder(User::class)->addMethods(['find'])->getMock();

        $userMock->expects($this->once())->method('find')->will($this->returnValue(null));

        $userMock->find($this->data['user_id']);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("User account not found");

        $this->accountService->getUserAccounts($this->data['id']);
    }

    public function test_getUserAccounts_should_return_user_accounts_when_user_exist_and_has_accounts()
    {
        $user = User::factory()->create();
        $accountType = AccountType::factory()->create();
        $account = Account::factory([
            'user_id'           => $user['id'],
            'account_type_id'   => $accountType['id']
        ])->create();

        $data['id'] = $user['id'];

        $userMock = $this->getMockBuilder(User::class)->addMethods(['find'])->onlyMethods(['accounts'])->getMock();

        $userMock->expects($this->once())->method('find')->willReturn($user);
        $userMock->expects($this->once())->willReturn('accounts')->willReturn($account);

        $userMock->find($data['id']);
        $userMock->accounts();

        $userAccounts = $this->accountService->getUserAccounts($data['id']);

        $this->assertCount(1, $userAccounts);
    }

    public function test_getAccountInfo_should_throw_exception_when_account_not_found()
    {
        $this->data['id'] = '90349-348342';

        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['find'])->getMock();

        $accountMock->expects($this->once())->method('find')->willReturn(null);

        $accountMock->find($this->data['id']);

        $this->expectExceptionMessage("Account not found");
        $this->expectException(ResourceNotFoundException::class);

        $this->accountService->getAccountInfo($this->data['id']);
    }

    public function test_getAccountInfo_should_return_account_resource_when_account_exist()
    {
        $user = User::factory()->create();
        $accountType = AccountType::factory()->create();
        $account = Account::factory([
            'user_id'          => $user['id'],
            'account_type_id'  => $accountType['id']
        ])->create();

        $data['id']  = $account['id'];

        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['find'])->getMock();

        $accountMock->expects($this->once())->method('find')->will($this->returnValue($account));
        $accountMock->find($account['id']);

        $response = $this->accountService->getAccountInfo($data['id']);

        $this->assertEquals($response['account_type'],   $account['->account_type_id']);
        $this->assertEquals($response['status'],   $account['status']);
        $this->assertEquals($response['account_number'],   $account['account_number']);
        $this->assertEquals($response['account_telephone'],   $account['account_telephone']);
        $this->assertEquals($response['current_balance'],   $account['current_balance']);
        $this->assertEquals($response['available_balance'],   $account['available_balance']);
        $this->assertEquals($response['currency'],   $account['currency']);

    }
}
