<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Validation\Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;

abstract class ValidationRuleTestCase extends TestCase
{
    /**
     * Create a new validator instance.
     *
     * @param  array $data
     * @param  array $rules
     * @param  string $locale
     * @return \Illuminate\Validation\Validator
     */
    protected function createValidator(array $data, array $rules, string $locale = 'en'): Validator
    {
        $translator = new Translator(new FileLoader(new Filesystem, null), $locale);

        return (new ValidatorFactory($translator))->make($data, $rules);
    }
}
