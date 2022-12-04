<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'first_name'                => 'required|string',
            'last_name'                 => 'required|string',
            'email'                     => 'required|email|unique:users,email',
            'telephone'                 => 'required|unique:users,telephone|max:9',
            'country'                   => 'required|string',
            'location'                  => 'required|string',
            'box_number'                => 'required|integer',
            'gender'                    => 'required|in:MALE,FEMALE, OTHER',
            'password'                  => 'required|confirmed|min:8',
            'dob'                       => 'required|date',
            'pob'                       => 'required'
        ];
    }
}
