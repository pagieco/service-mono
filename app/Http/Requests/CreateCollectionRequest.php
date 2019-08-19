<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Enums\DatabaseFieldType;
use Illuminate\Foundation\Http\FormRequest;

class CreateCollectionRequest extends FormRequest
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
     * @throws \ReflectionException
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'description' => 'max:250',
            'fields' => 'required|array',
            'fields.*.name' => 'required|min:3|max:100',
            'fields.*.slug' => 'required|min:3|max:100|alpha_dash',
            'fields.*.helptext' => 'max:255',
            'fields.*.is_required' => 'boolean',
            'fields.*.type' => [
                'required', Rule::in(DatabaseFieldType::getValues()),
            ],
        ];
    }
}
