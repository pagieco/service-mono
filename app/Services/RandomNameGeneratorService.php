<?php

namespace App\Services;

class RandomNameGeneratorService
{
    /**
     * Generate a string of random names.
     *
     * @param  int $length
     * @param  string $glue
     * @return string
     */
    public static function generate(int $length = 2, string $glue = ' '): string
    {
        $adjectives = config('random-names.adjectives');
        $nouns = config('random-names.nouns');

        $words = [];

        for ($i = 0; $i < ($length -1); $i++) {
            $words[] = $adjectives[rand(0, count($adjectives) -1)];
        }

        $words[] = $nouns[rand(0, count($nouns) - 1)];

        return implode($glue, $words);
    }
}
