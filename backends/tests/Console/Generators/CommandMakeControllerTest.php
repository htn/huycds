<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeControllerTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'controller', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_controller_with_default_backend_namespace()
    {
        $this->artisan('make:backend:controller', ['slug' => 'controller', 'name' => 'DefaultController']);

        $file = $this->finder->get(backend_path('controller').'/Http/Controllers/DefaultController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_controller_resource_with_default_backend_namespace()
    {
        $this->artisan('make:backend:controller', ['slug' => 'controller', 'name' => 'DefaultResourceController', '--resource' => 'resource']);

        $file = $this->finder->get(backend_path('controller').'/Http/Controllers/DefaultResourceController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_controller_with_custom_backend_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\CustomBackendNamespace\\');

        $this->artisan('make:backend:controller', ['slug' => 'controller', 'name' => 'CustomController']);

        $file = $this->finder->get(backend_path('controller').'/Http/Controllers/CustomController.php');

        $this->assertMatchesSnapshot($file);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('controller'));

        parent::tearDown();
    }
}