<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'in:manager,supervisor,admin'
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Name field is required and in string',
            'email.required' => 'Email is required',
            'email.email'=> 'Email must be a valid address',
            'password.required'=> 'Password is required with 8 characters and special characters',
            'password.min'=> 'Password must be at least 8 characters long with atleast 1 special character',
        ] ;
    }
}
