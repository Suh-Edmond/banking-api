<?php

namespace App\Services\Account;

use App\Constants\AccountStatus;
use App\Http\Resources\AccountBalanceResource;
use App\Http\Resources\AccountResource;
use App\Interfaces\Account\AccountInterface;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use App\Traits\CodeGenerationTrait;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class AccountService implements AccountInterface {

    use CodeGenerationTrait;

    public function createAccount($request)
    {
        $user = User::find($request->user_id);
        if(is_null($user)) {
            throw new ResourceNotFoundException("User account not found");
        }
        $account_type = AccountType::find($request->account_type_id);
        if(is_null($account_type)){
            throw new ResourceNotFoundException("Invalid Account type! The account_type_id does not exist");
        }
        return Account::create([
            'user_id'        => $user->id,
            'account_type_id' => $account_type->id,
            'status'         => AccountStatus::ACTIVE,
            'account_number' => $this->generateCode(8),
            'telephone'      => $request->telephone,
            'currency'       => 'XAF'
        ]);
    }

    public function getUserAccounts($id): array
    {
        $user = User::find($id);
        if(is_null($user)){
            throw new ResourceNotFoundException("User account not found");
        }
        $user_accounts = [];
        foreach($user->accounts as $account){
            array_push($user_accounts, new AccountResource($account, $user));
        }
        return $user_accounts;

    }

    public function getAccountInfo($id): AccountResource
    {
        $account = Account::find($id);
        if(is_null($account)){
            throw new ResourceNotFoundException("Account not found");
        }

        return new AccountResource($account, $account->user);
    }


    public function checkAccountBalance($request): AccountBalanceResource
    {
        $account = Account::where('account_number', $request->account_number)->get();
        if(is_null($account)) {
            throw new ResourceNotFoundException("No account found with this account number");
        }

        return new AccountBalanceResource($account[0]);
    }

}
