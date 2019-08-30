<?php

namespace App\Http\Requests;

use App\Http\Response;
use Illuminate\Foundation\Http\FormRequest;

class IdentifyProfileRequest extends FormRequest
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
     * Get the domain token fromt the request.
     *
     * @return string
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getDomainToken(): string
    {
        abort_if(! $token = $this->header('x-domain-token'), Response::HTTP_BAD_REQUEST);

        return $token;
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
            'new_email' => 'email|required_without_all:email,profile_id',
            'profile_id' => 'uuid|required_without:email',
        ];
    }
}
