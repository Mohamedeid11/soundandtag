<?php

namespace App\Http\Requests\Admin\Admins;

use Illuminate\Foundation\Http\FormRequest;

class EditAdmin extends FormRequest
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
            'name' => 'required',
            'username' => 'required|unique:admins,username,'.$this->admin->id,
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|email|unique:admins,email,'.$this->admin->id,
            'password' => 'nullable|min:8',
            'image' => 'nullable|image'
        ];
    }
}
