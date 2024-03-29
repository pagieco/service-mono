<?php

namespace App\Http\Requests;

use App\Rules\Timezone;
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
            'name' => 'required|min:5|max:100',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8|max:255',
            'timezone' => ['required', new Timezone],
        ];
    }
}
