<?php

namespace Huycds\Backends\Tests\Commands\Generators;

use Huycds\Backends\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeTestTest extends BaseTestCase
{
    use MatchesSnapshots;

    protected $finder;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];

        $this->artisan('make:backend', ['slug' => 'test', '--quick' => 'quick']);
    }

    /** @test */
    public function it_can_generate_a_new_test_with_default_backend_namespace()
    {
        $this->artisan('make:backend:test', ['slug' => 'test', 'name' => 'DefaultTest']);

        $file = $this->finder->get(backend_path('test').'/Tests/DefaultTest.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_new_test_with_custom_backend_namespace()
    {
        $this->app['config']->set('backends.namespace', 'App\\CustomTestNamespace\\');

        $this->artisan('make:backend:test', ['slug' => 'test', 'name' => 'CustomTest']);

        $file = $this->finder->get(backend_path('test').'/Tests/CustomTest.php');

        $this->assertMatchesSnapshot($file);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory(backend_path('test'));

        parent::tearDown();
    }
}