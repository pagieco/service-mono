<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use App\Services\RandomNameGeneratorService;

class RandomNameGeneratorServiceTest extends TestCase
{
    /** @test */
    public function it_generates_an_output_string()
    {
        $this->assertIsString(RandomNameGeneratorService::generate());
    }

    /** @test */
    public function it_generates_the_correct_amount_of_words()
    {
        $string = RandomNameGeneratorService::generate(5);

        $this->assertCount(5, explode(' ', $string));
    }

    /** @test */
    public function it_glues_the_words_correctly()
    {
        $glue = '-';

        $string = RandomNameGeneratorService::generate(5, $glue);

        $this->assertCount(5, explode($glue, $string));
    }

    /** @test */
    public function it_prefixes_the_name_with_adjectives()
    {
        $string = RandomNameGeneratorService::generate(5);
        $string = explode(' ', $string);

        $config = config('random-names.adjectives');

        array_pop($string);

        foreach ($string as $adjective) {
            $this->assertContains($adjective, $config);
        }
    }

    /** @test */
    public function it_suffixes_the_name_with_a_noun_from_the_nouns_array()
    {
        $string = RandomNameGeneratorService::generate();
        $string = explode(' ', $string);

        $config = config('random-names.nouns');

        $this->assertContains(end($string), $config);
    }
}
