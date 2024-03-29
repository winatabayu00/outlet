<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }
}
