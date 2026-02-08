<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Validation\Rule;
class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //Check whether or not the current request is 'PATCH'
        $isPatch = $this->isMethod('patch');
        return [
            'name' => [$isPatch?'sometimes':'required', 'string', 'max:255'],
            'lastname' => [$isPatch?'sometimes':'required', 'string', 'max:255'],
            'username' => [$isPatch?'sometimes':'required', 'string', 'max:255', 'unique:users,username'],
            'email' => [$isPatch?'sometimes':'required', 'email', 'unique:users,email',
            //Rule::unique('users')->ignore($this->route('user')),//<-Makes the program ignore when the user is being updated so that the unique check doesn't fail.
            ],
            //new validations
            'dui'=>[$isPatch?'sometimes':'required','string','regex:/^[0-9]{8}-[0-9]{1}$/', 'unique:users,dui'],
            'birth_date'=>[$isPatch?'sometimes':'required','date','before:today'],
            'phone_number'=>['nullable','string','regex:/^[6-7]{1}[0-9]{3}-[0-9]{4}$/','unique:users,phone_number'],
            'hiring_date'=>['sometimes','date']
        ];
    }
}
