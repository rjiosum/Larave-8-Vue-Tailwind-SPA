<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TestHelper;

    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }
}
