<?php

namespace App\Interfaces;

interface AuthenticationInterface {

    public function registerUser($request);

    public function loginUser($request);

}
