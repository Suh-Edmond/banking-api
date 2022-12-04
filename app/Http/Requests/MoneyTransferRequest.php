<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoneyTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_number_to'     => 'required|string',
            'account_number_from'   => 'required|string',
            'amount_deposited'      => 'required|numeric|between:1000,99999999999999',
            'transaction_date'      => 'required|string|date',
            'motive'                => 'required|string',
            'transfer_type_id'      => 'required|string',
        ];
    }
}
