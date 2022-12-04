<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'transaction_code'          => $this->transaction_code,
            'transaction_date'          => $this->transaction_date,
            'amount_deposited'          => $this->amount_deposited,
            'total_balance'             =>$this->total_balance,
            'motive'                    => $this->motive,
            'transaction_type'          => $this->transferType->name,
            'transaction_type_id'       => $this->transferType->id,
            'account_number_from'       => $this->account_number_from,
            'account_number_to'         => $this->account_number_to,
            'created_at'                => $this->created_at,
            'updated_by'                => $this->updated_at
        ];
    }
}
