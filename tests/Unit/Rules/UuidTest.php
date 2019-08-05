<?php

namespace Tests\Unit\Rules;

use App\Rules\Uuid;
use Illuminate\Support\Str;

class UuidTest extends ValidationRuleTestCase
{
    /** @test */
    public function it_passes_when_given_a_valid_uuid()
    {
        $validator = $this->createValidator(['uuid' => Str::uuid()], [
            'uuid' => ['required', new Uuid],
        ]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_given_an_invalid_uuid()
    {
        $validator = $this->createValidator(['uuid' => '123-456-789-514'], [
            'uuid' => ['required', new Uuid],
        ]);

        $this->assertFalse($validator->passes());
    }
}
