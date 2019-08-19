<?php

namespace Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Schema\Blueprint;

trait RefreshCollections
{
    public function setUp(): void
    {
        parent::setUp();

        if ($this->hasDependencies()) {
            return;
        }

        $this->dropAllCollections();
    }

    protected function dropAllCollections(): void
    {
        $mongo = DB::connection('mongodb');

        foreach ($mongo->listCollections() as $collection) {
            if (Str::startsWith($name = (string) $collection->getName(), 'system')) {
                continue;
            }

            (new Blueprint($mongo, $name))->drop();
        }
    }
}
