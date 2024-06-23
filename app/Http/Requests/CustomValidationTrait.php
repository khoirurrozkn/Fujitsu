<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait CustomValidationTrait
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                "status" => [
                    "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                    "description" => "Validation Error"
                ],
                "errors" => $validator->errors()->toArray()
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
