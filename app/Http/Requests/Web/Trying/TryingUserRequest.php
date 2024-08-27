<?php

namespace App\Http\Requests\Web\Trying;

use Illuminate\Foundation\Http\FormRequest;

class TryingUserRequest extends FormRequest
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
            'email' => 'required|email',
            // 'image' => 'image', # user image
            'video' => 'sometimes|mimetypes:video/mp4,video/mov,video/ogg,video/qt|max:10300',
            'first_name' => 'required',
            'last_name' => 'required',
            'first_name_file' => 'required|file', # record
            'last_name_file' => 'required|file', # record
        ];
    }
}
