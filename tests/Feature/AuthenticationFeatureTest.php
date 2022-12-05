<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AuthenticationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_registerUser_should_return_validation_error_when_a_required_field_is_missing()
    {
        $response = $this->post('/api/public/auth/register', [
            "first_name"    => "",
            "last_name"     => "",
            "email"         => "johnpeter@gmail.com",
            "telephone"     => "234892343",
            "country"       => "Cameroon",
            "location"      => "Buea",
            "box_number"    => 3,
            "gender"        => "MALE",
            "password"      => "password",
            "pob"           => "Buea",
            "password_confirmation"         => "password",
            "dob"           => "2022/11/24"
        ]);

        $response->assertSessionHasErrors('first_name');
        $response->assertSessionHasErrors('last_name');
    }

    public function test_registerUser_should_register_new_user_when_all_required_fields_are_provided()
    {
        Role::create([
            'name'          => 'CUSTOMER',
            'guard_name'   => 'api',
            'id'         => Uuid::uuid4()
        ]);

        $response = $this->post('/api/public/auth/register', [
            "first_name"    => "John",
            "last_name"     => "Peter",
            "email"         => "johnpeter@gmail.com",
            "telephone"     => "234892343",
            "country"       => "Cameroon",
            "location"      => "Buea",
            "box_number"    => 3,
            "gender"        => "MALE",
            "password"      => "password",
            "pob"           => "Buea",
            "password_confirmation"         => "password",
            "dob"           => "2022/11/24"
        ]);

        $this->assertCount(1, User::all());
        $response->assertOk();
    }

    public function test_loginUser_should_return_validation_error_when_required_field_not_provided()
    {
        $response = $this->post('/api/public/auth/login', [
            'email'         => '',
            'password'      => ''
        ]);

        $response->assertSessionHasErrorsIn('email');
        $response->assertSessionHasErrorsIn('password');

    }


    public function test_loginUser_should_generate_and_authentication_token_when_user_exist_and_required_fields_are_provided()
    {
        Role::create([
            'name'          => 'CUSTOMER',
            'guard_name'   => 'api',
            'id'         => Uuid::uuid4()
        ]);

        $this->post('/api/public/auth/register', [
            "first_name"    => "John",
            "last_name"     => "Peter",
            "email"         => "johnpeter@gmail.com",
            "telephone"     => "234892343",
            "country"       => "Cameroon",
            "location"      => "Buea",
            "box_number"    => 3,
            "gender"        => "MALE",
            "password"      => "password",
            "pob"           => "Buea",
            "password_confirmation"         => "password",
            "dob"           => "2022/11/24"
        ]);

        $response = $this->post('/api/public/auth/login', [
            'email'         => 'johnpeter@gmail.com',
            'password'      => 'password'
        ]);

        $response->assertOk();
        $data = json_decode($response->getContent());

        $this->assertEquals("Login Successfully", $data->message);
        $this->assertTrue($data->success);

        $this->assertNotNull($data->data->token);
        $this->assertCount(1, ($data->data->roles));
        $this->assertEquals("MALE", $data->data->gender);
        $this->assertEquals("Cameroon", $data->data->country);
        $this->assertEquals(3, $data->data->box_number);
        $this->assertEquals("Buea", $data->data->pob);
        $this->assertEquals("2022/11/24", $data->data->dob);
        $this->assertEquals("johnpeter@gmail.com", $data->data->email);
        $this->assertEquals("234892343", $data->data->telephone);
        $this->assertNotNull($data->data->id);

    }
}
