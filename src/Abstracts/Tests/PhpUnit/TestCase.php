<?php

namespace Laraneat\Core\Abstracts\Tests\PhpUnit;

use Laraneat\Core\Traits\TestsTraits\PhpUnit\TestsAuthHelperTrait;
use Laraneat\Core\Traits\TestsTraits\PhpUnit\TestsUrlHelperTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;

abstract class TestCase extends LaravelTestCase
{
    use TestsUrlHelperTrait,
        TestsAuthHelperTrait,
        RefreshDatabase;

    /**
     * Determine if the seed task should be run when refreshing the database.
     */
    protected bool $seed = true;

    /**
     * Set up the test environment, before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Reset the test environment, after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
