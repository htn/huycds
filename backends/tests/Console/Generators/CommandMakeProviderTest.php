<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeProviderTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'provider', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_provider_with_default_backend_namespace()
    {
        $this->artisan('make:backend:provider', ['slug' => 'provider', 'name' => 'DefaultProvider']);

        $file = $this->finder->get(backend_path('provider').'/Providers/DefaultProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_provider_with_custom_backend_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\CustomProviderNamespace\\');

        $this->artisan('make:backend:provider', ['slug' => 'provider', 'name' => 'CustomProvider']);

        $file = $this->finder->get(backend_path('provider').'/Providers/CustomProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('provider'));

        parent::tearDown();
    }
}