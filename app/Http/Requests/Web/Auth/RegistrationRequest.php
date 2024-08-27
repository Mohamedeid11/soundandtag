<?php

namespace App\Http\Requests\Web\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            "account_type" => "required|in:personal,corporate,employee",
            "username" => "required|min:7|unique:users,username|alpha_num",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|confirmed",
            "plan" => "int|exists:plans,id|required_unless:account_type,employee",
            'g-recaptcha-response' => 'required',
        ];
    }
}
