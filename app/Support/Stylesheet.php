<?php

namespace App\Support;

use App\Domain;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Stylesheet
{
    /**
     * @var string
     */
    protected $contents;

    public function compile(array $cssRules = []): Stylesheet
    {
        $this->contents = trim($this->reduceMediaQueries($cssRules));

        return $this;
    }

    public function store(Domain $domain)
    {
        $filename = sprintf('stylesheets/%s.css', $domain->id);

        Storage::put($filename, $this->contents);

        return Storage::url($filename).'?t='.Str::random();
    }

    /**
     * Get the current stylesheet contents.
     *
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    protected function reduceMediaQueries(array $rules): string
    {
        $queries = array_keys($rules);

        return array_reduce($queries, function (?array $carry, string $item) use ($rules): string {
            return sprintf('%s @media %s{%s}', $carry, $item, $this->reduceSelectors($rules[$item]));
        });
    }

    protected function reduceSelectors(array $rules): ?string
    {
        $selectors = array_keys($rules);

        return array_reduce($selectors, function (?array $carry, string $item) use ($rules): string {
            return sprintf('%s%s{%s}', $carry, $item, $this->reduceProperties($rules[$item]));
        });
    }

    protected function reduceProperties(array $rules): ?string
    {
        $properties = array_keys($rules);

        return array_reduce($properties, function (?array $carry, string $item) use ($rules): string {
            return sprintf('%s%s:%s;', $carry, $item, $rules[$item]);
        });
    }
}
