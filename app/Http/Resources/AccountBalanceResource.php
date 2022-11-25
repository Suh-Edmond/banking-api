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
            'account_type'       => $this->account->account_type,
            'current_balance'    => $this->account->current_balance,
            'available_balance'  => $this->account->available_balance,
            'bank_name'          => $this->account->bank_name,
            'bank_code'          => $this->account->bank_code
        ];
    }
}
