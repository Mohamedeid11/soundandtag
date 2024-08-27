<?php

namespace App\Http\Requests\Web\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditDetailsRequest extends FormRequest
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
        //last_name required with employee and personal
        $user = auth()->guard('user')->user();
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        return [
            'full_name' => Rule::requiredIf($user->account_type != 'corporate') . '|min:3',
            'last_name' =>  Rule::requiredIf($user->account_type != 'corporate')  . '|min:3',
            "business_name" => Rule::requiredIf($user->account_type == 'corporate') . '|min:3|max:30',
            'business_name_meaning' => 'sometimes|nullable',
            'address' => 'sometimes|nullable',
            'biography' => 'sometimes|nullable',
            'middle_name' => 'sometimes|nullable',
            'middle_name_meaning' => 'sometimes|nullable',
            'nick_name' => 'sometimes|nullable',
            'nick_name_meaning' => 'sometimes|nullable',
            'company' => 'sometimes|nullable',
            'company_meaning' => 'sometimes|nullable',
            'position' => Rule::requiredIf($user->account_type == 'employee') . '|min:3|sometimes|nullable',
            'interests' => 'sometimes|nullable',
            // 'email' => 'email|required|unique:users,email,'.$user->id,
            'country_id' => 'required|exists:countries,id',
            'phone' => 'nullable',
            'image' => 'nullable|image',
            'video' => 'mimetypes:video/mp4,video/mov,video/ogg,video/qt|max:10300',
            'website' => 'sometimes|nullable|regex:' . $regex
        ];

    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $combinedLength = strlen(
                $this->input('full_name') . ' ' . $this->input('middle_name') . ' ' . $this->input('last_name')
            );

            if ($combinedLength > 30) { // Adjust the max length as needed
                $validator->errors()->add('full_name', 'The combined length of the full name is too long.');
            }
        });
    }
}
