<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdentifyVisitorRequest extends FormRequest
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
            'email' => 'email|required_without:profile_id',
            'new_email' => 'email|required_with:email',
            'profile_id' => 'uuid|required_without:email',
        ];
    }
}