<?php declare(strict_types=1);
namespace Tests\Unit;

use App\Services\Account\AccountService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class AccountServiceTest extends TestCase
{
    use WithoutMiddleware;


    public function test_createAccount_should_throw_resource_not_found_exception_when_user_not_found()
    {

    }
}
