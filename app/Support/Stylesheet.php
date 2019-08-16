<?php

namespace App\Support;

use App\Domain;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Stylesheet
{
    public function compile(Domain $domain, array $cssRules = [])
    {
        $stylesheet = $this->reduceMediaQueries($cssRules);
        $filename = sprintf('public/stylesheets/%s.css', $domain->id);

        Storage::put($filename, $stylesheet);

        return Storage::url($filename).'?t='.Str::random();
    }

    protected function reduceMediaQueries(array $rules)
    {
        return array_reduce(array_keys($rules), function ($carry, $item) use ($rules) {
            return sprintf('%s @media %s{%s}', $carry, $item, $this->reduceRules($rules[$item]));
        });
    }

    protected function reduceRules(array $rules)
    {
        return array_reduce(array_keys($rules), function ($carry, $item) use ($rules) {
            return sprintf('%s%s{%s}', $carry, $item, $this->reduceProperties($rules[$item]));
        });
    }

    protected function reduceProperties(array $rules)
    {
        return array_reduce(array_keys($rules), function ($carry, $item) use ($rules) {
            return sprintf('%s%s:%s;', $carry, $item, $rules[$item]);
        });
    }
}
