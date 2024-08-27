<?php

namespace App\Http\Requests\Admin\Records;

use App\Models\RecordType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRecord extends FormRequest
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
        $user = $this->input('user_id') ? User::find($this->input('user_id')) : null;

        return [
            'user_id' => 'required|exists:users,id',
            'text_representation' => 'required',
            'record_type_id'=> ['required',
                Rule::in(
                    RecordType::where(function ($query) {$query->whereNull('user_id')->orWhere(['user_id'=> $this->input('user_id')]);})
                        ->where(['account_type'=>$user ? $user->account_type : null])->pluck('id')),
            Rule::unique('records')->where('user_id',  $user ? $user->id: null)->ignore($this->record->id)
            ],
            'file_path' => 'nullable',

        ];
    }
    public function messages(){
        $messages = parent::messages();
        $messages['record_type_id.in'] = __('validation.appropriate');
        return $messages;
    }
}
