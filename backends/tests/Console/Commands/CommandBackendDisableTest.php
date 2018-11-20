<?php

namespace Huycds\Backends\Tests\Commands\Commands;

use Huycds\Backends\Tests\BaseTestCase;

class CommandBackendDisableTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'disable', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_disable_an_enabled_backend()
    {
        $cached = \Backend::where('slug', 'disable');

        $this->assertTrue($cached->toArray()['enabled']);

        $this->artisan('backend:disable', ['slug' => 'disable']);

        $cached = \Backend::where('slug', 'disable');

        $this->assertFalse($cached->toArray()['enabled']);
    }

    /** @test */
    public function it_can_enable_a_disabled_backend()
    {
        $this->artisan('backend:disable', ['slug' => 'disable']);

        $cached = \Backend::where('slug', 'disable');

        $this->assertFalse($cached->toArray()['enabled']);

        $this->artisan('backend:enable', ['slug' => 'disable']);

        $cached = \Backend::where('slug', 'disable');

        $this->assertTrue($cached->toArray()['enabled']);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('disable'));

        parent::tearDown();
    }
}