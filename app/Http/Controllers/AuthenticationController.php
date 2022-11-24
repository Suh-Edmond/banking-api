<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    private $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }


    public function registerUser(RegisterUserRequest $registerUserRequest)
    {

        $this->authenticationService->registerUser($registerUserRequest);

        return $this->sendResponse(null, "User has been registered successfully", 201);
    }


    public function loginUser(LoginRequest $loginRequest)
    {
        $data = $this->authenticationService->loginUser($loginRequest);

        return $this->sendResponse($data, "Login Successfully", 200);
    }
}
