<?php

namespace App\Http\Requests\Web\General;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
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
        $authenticated = auth()->guard('user')->user();
        $name =  $authenticated ?  $authenticated->name : false;
        return [
            'name'    =>  $name ? '' : 'required',
            'email'   =>  $authenticated  ? '' : 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => 'required',
        ];
    }
}
