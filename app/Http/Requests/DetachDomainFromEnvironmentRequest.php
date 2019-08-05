<?php

namespace App\Http\Requests;

use App\Rules\Uuid;
use Illuminate\Foundation\Http\FormRequest;

class DetachDomainFromEnvironmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domain' => ['required', new Uuid],
        ];
    }
}
