<?php

namespace Huycds\Backends\Tests\Commands\Commands;

use Huycds\Backends\Tests\BaseTestCase;

class CommandBackendEnableTest extends BaseTestCase
{
    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'enable', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_enable_an_disabled_backend()
    {
        $this->artisan('backend:disable', ['slug' => 'enable']);

        $cached = \Backend::where('slug', 'enable');

        $this->assertFalse($cached->toArray()['enabled']);

        $this->artisan('backend:enable', ['slug' => 'enable']);

        $cached = \Backend::where('slug', 'enable');

        $this->assertTrue($cached->toArray()['enabled']);
    }

    /** @test */
    public function it_can_disable_a_enabled_backend()
    {
        $this->artisan('backend:enable', ['slug' => 'enable']);

        $cached = \Backend::where('slug', 'enable');

        $this->assertTrue($cached->toArray()['enabled']);

        $this->artisan('backend:disable', ['slug' => 'enable']);

        $cached = \Backend::where('slug', 'enable');

        $this->assertFalse($cached->toArray()['enabled']);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('enable'));

        parent::tearDown();
    }
}