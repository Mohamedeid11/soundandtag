<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

class EditUser extends FormRequest
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
            'country_id' => 'required|exists:countries,id',
            'username' => 'required|unique:users,id,'.$this->user->id,
            'email' => 'required|unique:users,email,'.$this->user->id,
            'name' => 'required',
            'password' => 'nullable',
        ];
    }
}
