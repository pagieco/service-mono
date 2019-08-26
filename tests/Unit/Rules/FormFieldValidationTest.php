<?php

namespace Tests\Unit\Rules;

use App\Rules\FormFieldValidation;
use App\Enums\FormFieldValidation as FormFieldValidationTypes;

class FormFieldValidationTest extends ValidationRuleTestCase
{
    /** @test */
    public function it_passes_when_given_a_valid_formfield_validation_rule()
    {
        $validator = $this->createValidator(['field' => [FormFieldValidationTypes::Email => true]], [
            'field' => new FormFieldValidation,
        ]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function it_fails_when_not_given_an_array_of_formfield_valdations()
    {
        $validator = $this->createValidator(['field' => FormFieldValidationTypes::Email], [
            'field' => new FormFieldValidation,
        ]);

        $this->assertFalse($validator->passes());
    }

    /** @test */
    public function it_fails_when_given_an_invalid_formfield_validation_rule()
    {
        $validator = $this->createValidator(['field' => ['unknown-validation-type' => true]], [
            'field' => new FormFieldValidation,
        ]);

        $this->assertFalse($validator->passes());
    }
}
