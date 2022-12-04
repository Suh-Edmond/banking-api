<?php

namespace App\Services\Account;

use App\Constants\AccountStatus;
use App\Http\Resources\AccountBalanceResource;
use App\Http\Resources\AccountCollection;
use App\Http\Resources\AccountResource;
use App\Interfaces\Account\AccountInterface;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class AccountService implements AccountInterface {

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
        $saved = Account::create([
            'user_id'        => $user->id,
            'account_type_id' => $account_type->id,
            'status'         => AccountStatus::ACTIVE,
            'account_number' => $this->generateAccountNumber(),
            'telephone'      => $request->telephone,
            'currency'       => 'XAF'
        ]);

        return $saved;
    }

    public function getUserAccounts($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            throw new ResourceNotFoundException("User account not found");
        }

        // dd($user->accounts->toArray());
        // dd($user->id);
        return new AccountCollection($user->accounts, $user);

    }

    public function getAccountInfo($id)
    {
        $account = Account::find($id);
        if(is_null($account)){
            throw new ResourceNotFoundException("Account not found");
        }

        return new AccountResource($account, $account->user);
    }


    public function checkAccountBalance($request)
    {
        $account = Account::where('account_number', $request->account_number)->get();
        if(is_null($account)) {
            throw new ResourceNotFoundException("No account found with this account number");
        }

        return new AccountBalanceResource($account[0]);
    }


    private function generateAccountNumber()
    {

        $accNum  = "";
        $current_year = date("Y");
        for($i = 0; $i < 8; $i++){
            $accNum = $accNum.rand(0, 9);
        }
        $accNum = $accNum.$current_year;
        return $accNum;
    }
}
