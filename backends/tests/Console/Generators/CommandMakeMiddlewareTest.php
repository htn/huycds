<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeMiddlewareTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'middleware', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_middleware_with_default_backend_namespace()
    {
        $this->artisan('make:backend:middleware', ['slug' => 'middleware', 'name' => 'DefaultMiddleware']);

        $file = $this->finder->get(backend_path('middleware').'/Http/Middleware/DefaultMiddleware.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_middleware_with_custom_backend_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\MiddlewareBackends\\');

        $this->artisan('make:backend:middleware', ['slug' => 'middleware', 'name' => 'CustomMiddleware']);

        $file = $this->finder->get(backend_path('middleware').'/Http/Middleware/CustomMiddleware.php');

        $this->assertMatchesSnapshot($file);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('middleware'));

        parent::tearDown();
    }
}