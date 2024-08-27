<?php

namespace App\Http\Requests\Admin\RecordTypes;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRecordType extends FormRequest
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
        $user_id = $this->input('user_id') == 0 ? null : $this->input('user_id');
        return [
            'name'=> ['required', Rule::unique('record_types')->where('user_id', $user_id)
                ->where('account_type', $this->input('account_type'))],
            'account_type'=> ['required', $user_id ? Rule::in([User::find($user_id)->account_type]) : 'in:personal,corporate'],
            'type_order' => ['required', Rule::unique('record_types')->where('user_id', $user_id)
                ->where('account_type', $this->input('account_type')) ],
        ];
    }
    public function messages(){
        $messages = parent::messages();
        $messages['account_type.in'] = __('validation.appropriate');
        return $messages;
    }
}
