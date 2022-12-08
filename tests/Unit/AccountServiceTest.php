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

    public function testCreateAccountShouldCreateAnAccountWhenUserAccountExistAndAccountTypeExist()
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

    public function testCreateAccountShouldThrowResourceNotFoundExceptionWhenUserAccountNotExist()
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

    public function testCreateAccountShouldThrowResourceNotFoundExceptionWhenAccountTypeNotExist()
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

    public function testGetUserAccountsShouldThrowResourceNotFoundWhenUserDoesNotExist()
    {
        $this->data['id'] = '9202323';
        $userMock = $this->getMockBuilder(User::class)->addMethods(['find'])->getMock();

        $userMock->expects($this->once())->method('find')->will($this->returnValue(null));

        $userMock->find($this->data['user_id']);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("User account not found");

        $this->accountService->getUserAccounts($this->data['id']);
    }

    public function testGetUserAccountsShouldReturnUserAccountsWhenUserExistAndHasAccounts()
    {
        $user = User::factory()->create();
        $accountType = AccountType::factory()->create();
        $account = Account::factory([
            'user_id'           => $user['id'],
            'account_type_id'   => $accountType['id']
        ])->create();

        $data['id'] = $user['id'];

        $userMock = $this->getMockBuilder(User::class)->addMethods(['find'])->getMock();

        $userMock->expects($this->once())->method('find')->willReturn($user);

        $userMock->find($data['id']);

        $userAccounts = $this->accountService->getUserAccounts($data['id']);

        $this->assertCount(1, $userAccounts);
        $this->assertEquals($account['user_id'], $userAccounts[0]->user_id);
        $this->assertEquals($account['id'], $userAccounts[0]->id);
        $this->assertEquals($account['account_type_id'], $userAccounts[0]->account_type_id);
        $this->assertEquals($account['current_balance'], $userAccounts[0]->current_balance);
        $this->assertEquals($account['available_balance'], $userAccounts[0]->available_balance);
        $this->assertEquals($account['currency'], $userAccounts[0]->currency);
    }

    public function testGetAccountInfoShouldThrowExceptionWhenAccountNotFound()
    {
        $this->data['id'] = '90349-348342';

        $accountMock = $this->getMockBuilder(Account::class)->addMethods(['find'])->getMock();

        $accountMock->expects($this->once())->method('find')->willReturn(null);

        $accountMock->find($this->data['id']);

        $this->expectExceptionMessage("Account not found");
        $this->expectException(ResourceNotFoundException::class);

        $this->accountService->getAccountInfo($this->data['id']);
    }

    public function testGetAccountInfoShouldReturnAccountResourceWhenAccountExist()
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
