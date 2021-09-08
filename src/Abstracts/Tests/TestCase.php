<?php

namespace Laraneat\Core\Abstracts\Tests;

use Laraneat\Core\Traits\TestsTraits\TestsAuthHelperTrait;
use Laraneat\Core\Traits\TestsTraits\TestsModelHelperTrait;
use Laraneat\Core\Traits\TestsTraits\TestsUrlHelperTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;

abstract class TestCase extends LaravelTestCase
{
    use TestsAuthHelperTrait,
        TestsModelHelperTrait,
        TestsUrlHelperTrait,
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
