<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Support\Arr;

abstract class PolicyTestCase extends TestCase
{
    /**
     * @var array
     */
    protected $policyList = [];

    /** @test */
    public function it_correctly_refects_to_list_of_policies()
    {
        foreach ($this->policyList as $policy) {
            $this->assertNotNull(
                Arr::get(config('auth.permissions'), $policy),
                sprintf('Unreflected policy found: %s', $policy)
            );
        }
    }
}
