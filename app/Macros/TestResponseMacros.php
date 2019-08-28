<?php

namespace App\Macros;

use Tests\OpenApiSchemaValidator;
use Illuminate\Foundation\Testing\TestResponse;

trait TestResponseMacros
{
    public static function registerTestResponseMacros()
    {
        if (app()->environment('testing')) {
            TestResponse::macro('assertSchema', function (string $operationId, int $statusCode = 200) {
                (new OpenApiSchemaValidator($this))->assertSchema($operationId, $statusCode);
            });
        }
    }
}
