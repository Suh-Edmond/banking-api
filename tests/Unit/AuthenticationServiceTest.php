<?php

namespace Tests\Unit;

use App\Models\CustomRole;
use App\Models\User;
use App\Services\Auth\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Validation\UnauthorizedException;

class AuthenticationServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthenticationService $authenticationService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticationService = new AuthenticationService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterUserShouldCreateNewUser()
    {
        $user = User::factory()->create();

        $data = new Request([
            'first_name'  => 'John Doe',
            'last_name'   => 'Doe',
            'email'       => 'email@gmail.com',
            'telephone'   => '673908123',
            'dob'         => '2022/12/12',
            'pob'         => 'Buea',
            'country'     => 'Cameroon',
            'location'    => 'Buea',
            'box_number'  => 90,
            'gender'      => 'MALE',
            'password'    => 'password'
        ]);

        $role = CustomRole::factory()->create();

        $user->assignRole($role);

        try {
            $this->authenticationService->registerUser($data);
        } catch (\Exception $e) {
        }

        $this->assertDatabaseHas('users', [
            'email'         => $data['email'],
            'first_name'    => $data['first_name']
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id'       => $role['id'],
            'model_id'       => $user['id']
        ]);
    }

    public function testLoginUserShouldLoginUserWhenValidCredentials()
    {
        $user = User::factory([
            'email' => 'email@gmail.com',
            'password' => Hash::make('password')
        ])->create();

        $role = CustomRole::factory()->create();

        $user->assignRole($role);

        $data = new Request([
            'email'    => 'email@gmail.com',
            'password' => 'password'
        ]);

        $mock = $this->getMockBuilder(User::class)->addMethods(['first'])->getMock();
        $mock->expects($this->once())->method('first')->willReturn($user);

        $mock->first();


        $response = $this->authenticationService->loginUser($data);

        $jsonResponse = json_decode($response->response()->content());


        $this->assertNotNull($jsonResponse->data->token);

        $this->assertEquals($user->first_name, $jsonResponse->data->first_name);
        $this->assertEquals($user->last_name, $jsonResponse->data->last_name);
        $this->assertEquals($user->email, $jsonResponse->data->email);
        $this->assertEquals($user->telephone, $jsonResponse->data->telephone);
        $this->assertEquals($user->country, $jsonResponse->data->country);

        $this->assertEquals($role->id, $jsonResponse->data->roles[0]->pivot->role_id);
        $this->assertEquals($role->name, $jsonResponse->data->roles[0]->name);
    }

    public function testLoginUserShouldThrowExceptionWhenUserNotFound()
    {
        $data = new Request([
            'email'    => 'email@gmail.com',
            'password' => 'password'
        ]);

        $mock = $this->getMockBuilder(User::class)->addMethods(['first'])->getMock();
        $mock->expects($this->once())->method('first')->willReturn(null);

        $mock->first();

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage("Invalid credentials! Please try again.");

        $this->authenticationService->loginUser($data);
    }


}
