<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountBalanceResource extends JsonResource
{
    private $account;

    public function __construct($account)
    {
        $this->account = $account;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'account_number'     => $this->account->account_number,
            'account_type'       => $this->account->accountType->name,
            'current_balance'    => $this->account->current_balance,
            'available_balance'  => $this->account->available_balance,
            'telephone'          => $this->account->telephone,
            'currency'           => $this->account->currency
        ];
    }
}
