<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    private $user;
    public function __construct($resource, $user)
    {
        parent::__construct($resource);
        $this->user = $user;
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
            'id'                => $this->id,
            'user_name'         => $this->user->first_name . " " . $this->user->last_name,
            'email'             => $this->user->email,
            'user_telephone'    => $this->user->telephone,
            'gender'            => $this->user->gender,
            'country'           => $this->user->country,
            'account_type'      => $this->accountType->name,
            'account_status'    => $this->status,
            'account_number'    => $this->account_number,
            'account_telephone' => $this->telephone,
            'current_balance'   => $this->current_balance,
            'available_balance' => $this->available_balance,
            'currency'          => $this->currency,
            'created_at'        => $this->created_at,
            'updated_by'        => $this->updated_at
        ];
    }
}
