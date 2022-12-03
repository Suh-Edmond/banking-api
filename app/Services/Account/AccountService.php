<?php

namespace App\Services\Account;

use App\Constants\AccountStatus;
use App\Constants\BankInfo;
use App\Http\Resources\AccountBalanceResource;
use App\Http\Resources\AccountResource;
use App\Interfaces\Account\AccountInterface;
use App\Models\Account;
use App\Models\User;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Illuminate\Support\Str;

class AccountService implements AccountInterface {

    public function createAccount($request)
    {
        $user = User::find($request->user_id);
        if(! $user) {
            throw new ResourceNotFoundException();
        }
        $created = Account::create([
            'user_id'        => $user->id,
            'acount_type'    => $request->account_type,
            'status'         => AccountStatus::ACTIVE,
            'account_number' => $this->generateAccountNumber(),
            'telephone'      => $request->telephone,
        ]);

        return new AccountResource($created, $user);
    }

    public function getAccountInfo($id)
    {
        $account = Account::findOrFail($id);

        return new AccountResource($account, $account->user);
    }


    public function checkAccountBalance($request)
    {
        $account = Account::where('account_number', $request->account_number)->get();
        if(! $account) {
            throw new ResourceNotFoundException("No account found with this account number");
        }

        return new AccountBalanceResource($account);
    }


    private function generateAccountNumber()
    {
        $uuid         = Str::uuid()->toString();
        $uuid         = trim($uuid, '-');
        $current_year = date("Y");
        $uuid         =  substr($uuid, 0, 14);
        $accNum       = $uuid.$current_year;

        return $accNum;
    }
}
