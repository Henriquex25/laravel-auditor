<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;

abstract class BaseTestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    /**
     * @psalm-template RealInstanceType of object
     *
     * @psalm-param class-string<RealInstanceType> $originalClassName
     *
     * @psalm-return MockObject&RealInstanceType
     */
    protected function createMock(string $originalClassName): MockObject
    {
    }
}

abstract class TestCase extends BaseTestCase
{
    //
}
