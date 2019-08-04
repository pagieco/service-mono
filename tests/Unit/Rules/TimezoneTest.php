<?php

namespace Tests\Unit\Rules;

use App\Rules\Timezone;

class TimezoneTest extends ValidationRuleTestCase
{
    /** @test */
    public function it_passes_when_given_a_valid_timezone()
    {
        $validator = $this->createValidator(['timezone' => 'Europe/Amsterdam'], [
            'timezone' => ['required', new Timezone],
        ]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_given_an_invalid_timezone()
    {
        $validator = $this->createValidator(['timezone' => 'Mars/HellasBasin'], [
            'timezone' => ['required', new Timezone],
        ]);

        $this->assertFalse($validator->passes());
    }
}
