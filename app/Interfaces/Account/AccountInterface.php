<?php
namespace App\Interfaces\Account;

interface AccountInterface {

    public function createAccount($request);

    public function getAccountInfo($id);

    public function checkAccountBalance($request);

}
