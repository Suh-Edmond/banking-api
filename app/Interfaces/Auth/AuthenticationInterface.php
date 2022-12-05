<?php

namespace App\Interfaces\Auth;

interface AuthenticationInterface {

    public function registerUser($request);

    public function loginUser($request);

}
